<?php
// Include database connection
require_once '../includes/DB-con.php';

// Set content type for JSON response
header('Content-Type: application/json');
// السماح بطلبات API من الموبايل والمتصفح
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Function to send JSON response
function sendResponse($status, $statusCode, $message, $data = []) {
    http_response_code($statusCode);
    echo json_encode([
        "status" => $status,
        "status_code" => $statusCode,
        "message" => $message,
        "data" => $data
    ]);
    if ($statusCode === 200){
        unset($_COOKIE['EMAIL']); // Ensure it's removed from global variables
    }
    global $conn;
    $conn->close(); // Close DB connection
    exit;
}

// Retrieve email from cookies
if (!isset($_COOKIE['EMAIL']) || empty(trim($_COOKIE['EMAIL']))) {
    sendResponse("error", 400, "Email is required and cannot be blank.");
}

$email = trim($_COOKIE['EMAIL']);

// Get the input from the request body
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($input['otp'])) {
    sendResponse("error", 400, "OTP is required.");
}

$otp = trim($input['otp']);

// Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/\s/', $email)) {
    sendResponse("error", 400, "Invalid email format. Email cannot contain whitespace.");
}

// Validate OTP
if (!preg_match('/^\d{4}$/', $otp)) {
    sendResponse("error", 400, "Invalid OTP format. OTP must be a 4-digit number.");
}

// Check if the email and OTP match in the database
$query = "SELECT USER_ID, EXPIRY_TIME, USER_NAME FROM USER WHERE EMAIL = ? AND OTP = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}

$otp = (int) $otp; // Ensure OTP is an integer
$stmt->bind_param("si", $email, $otp);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Query execution error: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    sendResponse("error", 401, "Invalid OTP or email.");
}

$user = $result->fetch_assoc();

// Check if the OTP is expired
if ($user['EXPIRY_TIME'] < time()) {
    sendResponse("error", 410, "OTP has expired.");
}

// Activate the email and reset OTP
$updateQuery = "UPDATE USER SET EMAIL_ACTIVATION = 1, OTP = NULL, EXPIRY_TIME = NULL WHERE USER_ID = ?";
$stmt = $conn->prepare($updateQuery);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}
$stmt->bind_param("i", $user['USER_ID']);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Failed to update user data.");
}

// ضبط الكوكيز بطريقة تدعم الموبايل والمتصفح
$cookie_expire = time() + (86400 * 30); // 30 يوم
$cookie_path = "/"; // متاحة لكل المسارات
$cookie_domain = ""; // ضع نطاق موقعك هنا إذا لزم الأمر
$cookie_secure = false; // يفضل جعله true إذا كان لديك HTTPS
$cookie_httponly = false; // يجب أن يكون false ليتمكن الموبايل من قراءتها
$cookie_samesite = "None"; // يضمن أن الكوكيز تُرسل مع جميع الطلبات (مهمة للموبايل)

setcookie("HK", $user['USER_ID'], [
    'expires' => $cookie_expire,
    'path' => $cookie_path,
    'secure' => $cookie_secure,
    'httponly' => $cookie_httponly
]);

setcookie("USER_NAME", $user['USER_NAME'], [
    'expires' => $cookie_expire,
    'path' => $cookie_path,
    'secure' => $cookie_secure,
    'httponly' => $cookie_httponly
]);

sendResponse("success", 200, "OTP verified successfully. Email has been activated.");
?>
