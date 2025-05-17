<?php
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require_once('../vendor/autoload.php');

use GuzzleHttp\Client;

$client = new Client();
// تحميل ملف .env
$dotenv = Dotenv\Dotenv::createImmutable('../'); // تأكد من أن المسار صحيح
$dotenv->load();

require '../includes/DB-con.php';

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// قبول POST فقط
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status"=> "error", "error" => "Only POST method is allowed"]);
    exit;
}

// قراءة JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(["status"=> "error", "error" => "Invalid JSON input"]);
    exit;
}

// جلب المتغيرات من .env
$tapId  = trim($input['TAB_ID'] ?? '');
$secretKey = $_ENV['TAP_SECRET_KEY'] ?? '';

// جلب القيم
$reason = trim($input['REASON']  ?? '');
$userId = intval($input['USER_ID']?? 0);
$amount = floatval($input['AMOUNT']?? 0.0);

// التحقق من القيم
if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(["status"=> "error", "error" => "USER_ID (positive integer) is required"]);
    exit;
}
if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(["status"=> "error", "error" => "AMOUNT (positive number) is required"]);
    exit;
}
if ($tapId === '') {
    http_response_code(400);
    echo json_encode(["status"=> "error", "error" => "TAB_ID (non-empty) is required"]);
    exit;
}
if ($reason === '') {
    http_response_code(400);
    echo json_encode(["status"=> "error", "error" => "REASON (non-empty) is required"]);
    exit;
}


if (!empty($tapId)) {
    try {
        $response = $client->request('GET', 'https://api.tap.company/v2/charges/' . $tapId, [
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




// تحقق إذا كان tapId موجود مسبقًا
$checkStmt = $conn->prepare("SELECT 1 FROM PAYMENTS WHERE TAB_ID = ? LIMIT 1");
if (!$checkStmt) {
    http_response_code(500);
    echo json_encode(["status"=> "error", "error" => "Prepare check failed: " . $conn->error]);
    exit;
}

$checkStmt->bind_param("s", $tapId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode([
        "status"  => "Duplicate",
        "message" => "TAB_ID already exists"
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();


// جهّز INSERT (بإضافة عمود AMOUNT)
$stmt = $conn->prepare("
    INSERT INTO PAYMENTS (REASON, TAB_ID, USER_ID, AMOUNT)
    VALUES (?, ?, ?, ?)
");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["status"=> "error", "error" => "Prepare failed: " . $conn->error]);
    exit;
}

// ربط المتغيرات: s = string, i = integer, d = double
$stmt->bind_param("ssid", $reason, $tapId, $userId, $amount);

// تنفيذ الإدراج
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        "status"     => "success",
        "message"    => "Payment record created successfully",
        "payment_id" => $stmt->insert_id
    ]);
} else {
    // فحص التكرار برقم الخطأ
    if ($stmt->errno === 1062) {
        http_response_code(409);
        echo json_encode([
            "status"  => "error",
            "message" => "tapId already exists"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status"=> "error",
            "error" => "Insertion failed: " . $stmt->error
        ]);
    }
}

// إغلاق
$stmt->close();
$conn->close();
