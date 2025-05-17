<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Set response type to JSON


require '../includes/DB-con.php';
require_once('../vendor/autoload.php');
use GuzzleHttp\Client;

$client = new Client();
// تحميل ملف .env
$dotenv = Dotenv\Dotenv::createImmutable('../'); // تأكد من أن المسار صحيح
$dotenv->load();
$secretKey = $_ENV['TAP_SECRET_KEY'] ?? '';

// التعامل مع طلبات الـ OPTIONS للـ CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// السماح فقط بـ POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method is allowed"]);
    exit;
}

// قراءة JSON من جسم الطلب
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON input"]);
    exit;
}

// جلب القيم
$errors = [];

if (!isset($input['fees']) || !is_numeric($input['fees']) || floatval($input['fees']) < 0) {
    $errors[] = 'الرسوم غير صحيحة.';
} else {
    $fees = floatval($input['fees']);
}

if (!isset($input['user_id']) || !is_numeric($input['user_id']) || intval($input['user_id']) <= 0) {
    $errors[] = 'معرّف المستخدم غير صالح.';
} else {
    $userId = intval($input['user_id']);
}

if (!isset($input['product_id']) || !is_numeric($input['product_id']) || intval($input['product_id']) <= 0) {
    $errors[] = 'معرّف المنتج غير صالح.';
} else {
    $productId = intval($input['product_id']);
}

if (!isset($input['tap_id']) || trim($input['tap_id']) === '') {
    $errors[] = 'معرّف Tap مطلوب.';
} else {
    $tap_id = trim($input['tap_id']);
}



if (!empty($tap_id)) {
    try {
        $response = $client->request('GET', 'https://api.tap.company/v2/charges/' . $tap_id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $secretKey,
                'accept' => 'application/json',
            ],
        ]);

        $response = $response->getBody();
        $response = json_decode($response, true);
        if ($response['status'] !== 'CAPTURED') {
            http_response_code(400);
            echo json_encode(["status"=> "error", "error" => "Payment not captured"]);
            exit;
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        // echo json_encode(["status"=> "error", "error" => "API request failed: " . $e->getMessage()]);
        echo json_encode(["status"=> "error", "error" => "Not Authorized or Access Denied"]);
        exit;
    }
}

// تحقق من وجوده في PAYMENTS
$checkStmt = $conn->prepare("SELECT TAB_ID FROM PAYMENTS WHERE TAB_ID = ? LIMIT 1");
if (!$checkStmt) {
    http_response_code(500);
    echo json_encode(["status"=> "error", "error" => "Prepare check failed (PAYMENTS): " . $conn->error]);
    exit;
}
$checkStmt->bind_param("s", $tap_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows <= 0) {
    http_response_code(409);
    echo json_encode([
        "status"  => "error",
        "message" => "TAB_ID not found in PAYMENTS",
        "TAB_ID" => $tap_id
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// تحقق من عدم وجوده في Enrollments (لتفادي التكرار)
$checkStmt = $conn->prepare("SELECT tap_id FROM Enrollments WHERE tap_id = ? LIMIT 1");
if (!$checkStmt) {
    http_response_code(409);
    echo json_encode(["status"=> "error", "error" => "Prepare check failed Enrollments: " . $conn->error]);
    exit;
}
$checkStmt->bind_param("s", $tap_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode([
        "status"  => "Duplicate",
        "message" => "TAB_ID already exists in Enrollments"
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

$checkProduct = $conn->prepare("SELECT I_ID FROM ITEM WHERE I_ID = ? LIMIT 1");
$checkProduct->bind_param("i", $productId);
$checkProduct->execute();
$checkProduct->store_result();

if ($checkProduct->num_rows <= 0) {
    http_response_code(400);
    echo json_encode([
        "status"  => "error",
        "message" => "Product ID غير موجود في جدول ITEM"
    ]);
    $checkProduct->close();
    $conn->close();
    exit;
}
$checkProduct->close();


// تحضير الاستعلام
$stmt = $conn->prepare("
    INSERT INTO Enrollments (fees, user_id, product_id, tap_id)
    VALUES (?, ?, ?, ?)
");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

// ربط المتغيرات
$stmt->bind_param("diis", $fees, $userId, $productId, $tap_id);

// تنفيذ الإدراج
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        "message"        => "Enrollment created successfully",
        "enrollment_id"  => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Insertion failed: " . $stmt->error]);
}

// إغلاق الموارد
$stmt->close();
$conn->close();
