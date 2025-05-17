<?php
header("Content-Type: application/json");

require_once '../includes/DB-con.php';

function sendResponse($status, $message, $data = []) {
    http_response_code($status);
    echo json_encode([
        "status" => $status === 200 ? "success" : "error",
        "status_code" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

// ✅ التحقق من نوع الطلب
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(405, "Invalid request method. Use POST.");
}

$errors = [];

// 1. USER_ID & USER_NAME (from cookies)
$USER_ID = $_COOKIE['HK'] ?? '';
$USER_NAME = $_COOKIE['USER_NAME'] ?? '';
if (empty($USER_ID)) $errors[] = "USER_ID (cookie 'HK') is required";
if (empty($USER_NAME)) $errors[] = "USER_NAME (cookie 'USER_NAME') is required";

// 2. Required text fields
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$location = trim($_POST['location'] ?? '');
$tapId = trim($_POST['TAB_ID'] ?? '');

if ($name === '') $errors[] = "Name is required";
if ($description === '') $errors[] = "Description is required";
if ($location === '') $errors[] = "Location is required";
if ($tapId === '') $errors[] = "TAB_ID is required";

// 3. Prices
$starting_price = floatval($_POST['starting_price'] ?? 0);
$expected_price = floatval($_POST['expected_price'] ?? 0);
if ($starting_price <= 0) $errors[] = "Starting price must be a positive number";
if ($expected_price <= 0) $errors[] = "Expected price must be a positive number";
if ($expected_price < $starting_price) $errors[] = "Expected price must be greater than or equal to the starting price";

// 4. Dates
$start_date_str = $_POST['start_date'] ?? '';
$delivery_date_str = $_POST['delivery_date'] ?? '';
$start_date = @date('Y-m-d', strtotime($start_date_str));
$delivery_date = @date('Y-m-d', strtotime($delivery_date_str));

if (!$start_date_str || !$start_date || $start_date === '1970-01-01') {
    $errors[] = "Valid start date is required";
}
if (!$delivery_date_str || !$delivery_date || $delivery_date === '1970-01-01') {
    $errors[] = "Valid delivery date is required";
}
if ($start_date && $delivery_date && strtotime($start_date) < strtotime($delivery_date)) {
    $errors[] = "Delivery date must be after start date";
}

// 5. Period of bid
$period_of_bid = intval($_POST['period_of_bid'] ?? 0);
if ($period_of_bid <= 0) $errors[] = "Period of bid must be a positive integer";

// 6. Category ID
$category_id = intval($_POST['category_id'] ?? 0);
if ($category_id <= 0) $errors[] = "Valid category ID is required";

// Final validation response
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "errors" => $errors
    ]);
    exit;
}


// تحقق من وجوده في PAYMENTS
$checkStmt = $conn->prepare("SELECT TAB_ID FROM PAYMENTS WHERE TAB_ID = ? LIMIT 1");
if (!$checkStmt) {
    http_response_code(500);
    echo json_encode(["status"=> "error", "error" => "Prepare check failed (PAYMENTS): " . $conn->error]);
    exit;
}
$checkStmt->bind_param("s", $tapId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows <= 0) {
    http_response_code(409);
    echo json_encode([
        "status"  => "error",
        "message" => "TAB_ID not found in PAYMENTS"
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// تحقق من عدم وجوده في ITEM (لتفادي التكرار)
$checkStmt = $conn->prepare("SELECT TAB_ID FROM ITEM WHERE TAB_ID = ? LIMIT 1");
if (!$checkStmt) {
    http_response_code(409);
    echo json_encode(["status"=> "error", "error" => "Prepare check failed (ITEM): " . $conn->error]);
    exit;
}
$checkStmt->bind_param("s", $tapId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode([
        "status"  => "Duplicate",
        "message" => "TAB_ID already exists in ITEM"
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();


// ✅ التحقق من وجود صور مرفوعة
if (!isset($_FILES['photo'])) {
    sendResponse(400, "No valid images uploaded.");
}

$files = $_FILES['photo'];

// ✅ تحويل الملف المفرد إلى مصفوفة
if (!is_array($files['name'])) {
    $files = [
        'name' => [$files['name']],
        'type' => [$files['type']],
        'tmp_name' => [$files['tmp_name']],
        'error' => [$files['error']],
        'size' => [$files['size']]
    ];
}

// 🔹 وظيفة رفع الصور
function uploadImage($file, $upload_dir = "../user_data/", $user_name) {
    $base_url = "https://hk.herova.net/";

    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0775, true) && !is_dir($upload_dir)) {
            sendResponse(500, "Failed to create upload directory.");
        }
    }

    if (!is_writable($upload_dir)) {
        sendResponse(500, "Upload directory is not writable.");
    }

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/jpg", "image/webp"];
    if (!in_array($file['type'], $allowed_types)) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = $user_name . '_' . uniqid("", true) . "." . $ext;
    $file_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return $base_url . "user_data/" . $new_filename;
    } else {
        return null;
    }
}

// ✅ قائمة لحفظ الصور الخاصة بالمنتج الجديد
$photo_urls = [];
$photos_sent = []; // ✅ حفظ قائمة الصور المرسلة في الطلب

// 🔹 رفع الصور
foreach ($files['name'] as $key => $photo_name) {
    $file = [
        'name' => $files['name'][$key],
        'type' => $files['type'][$key],
        'tmp_name' => $files['tmp_name'][$key],
        'error' => $files['error'][$key],
        'size' => $files['size'][$key]
    ];

    $photos_sent[] = $photo_name; // ✅ إضافة الصورة إلى قائمة الصور المرسلة

    $uploaded_image = uploadImage($file, "../user_data/", $USER_NAME);
    if ($uploaded_image) {
        $photo_urls[] = $uploaded_image;
    }

    usleep(500000); // 🔸 تأخير بين رفع كل صورة
}

// ✅ التحقق من أن جميع الصور تم رفعها
if (empty($photo_urls)) {
    sendResponse(500, "No images were uploaded.");
}

// ✅ تحويل المصفوفة إلى JSON لتخزينها في قاعدة البيانات
$photo_urls_json = json_encode($photo_urls, JSON_UNESCAPED_SLASHES);

// 🔹 إدراج المنتج في قاعدة البيانات مع الصور الخاصة به فقط
try {
    $stmt = $conn->prepare("INSERT INTO ITEM (TAB_ID, PHOTO, ITEM_NAME, DESCRIPTION, STARTING_PRICE, EXPECTED_PRICE, LOCATION, START_DATE, DELIVERED_DATE, PERIOD_OF_BID, CAT_ID, POST_BY, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssddsssiii", $tapId, $photo_urls_json, $name, $description, $starting_price, $expected_price, $location, $start_date, $delivery_date, $period_of_bid, $category_id, $USER_ID);

    if ($stmt->execute()) {
        sendResponse(200, "Product added successfully.", [
            "id" => $stmt->insert_id,
            "photos_sent" => $photos_sent, // ✅ عرض الصور التي تم إرسالها في الطلب
            "photos_uploaded" => json_decode($photo_urls_json, true) // ✅ عرض الصور التي تم رفعها بنجاح
        ]);
    } else {
        throw new Exception("Failed to add product: " . $stmt->error);
    }
} catch (Exception $e) {
    sendResponse(500, $e->getMessage());
} finally {
    $stmt->close();
    $conn->close();
}

?>
