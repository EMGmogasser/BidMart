<?php
// Include database connection
require_once '../includes/DB-con.php';

// Include libphonenumber for phone number validation
require_once '../vendor/autoload.php';
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

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
    exit;
}

// Get the input from the request body
$input = json_decode(file_get_contents('php://input'), true);

// Validate if all required fields are provided
$requiredFields = ['name', 'email', 'phone', 'password'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        sendResponse("error", 400, "All fields ($field) are required and cannot be blank.");
    }
}

$name = trim($input['name']);
$email = trim($input['email']);
$phone = trim($input['phone']);
$password = trim($input['password']);

// Validate Name
if (strlen($name) < 3 || strlen($name) > 25) {
    sendResponse("error", 400, "Name must be between 3 and 25 characters long.");
}

if (!preg_match("/^[a-zA-Z]/", $name)) {
    sendResponse("error", 400, "Name cannot start with a number or symbol and must start with a letter.");
}

if (!preg_match("/^[a-zA-Z0-9_]+$/", $name)) { 
    sendResponse("error", 400, "Name should only contain letters, numbers, and underscores."); 
}

// Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/\s/', $email)) {
    sendResponse("error", 400, "Invalid email format. Email cannot contain whitespace.");
}

if (!preg_match("/^[a-zA-Z]/", $email)) {
    sendResponse("error", 400, "Email cannot start with a number or symbol and must start with a letter.");
}

// Validate Phone
if (preg_match('/\s/', $phone)) {
    sendResponse("error", 400, "Phone number cannot contain whitespace.");
}

$phoneUtil = PhoneNumberUtil::getInstance();
try {
    $phoneNumberObject = $phoneUtil->parse($phone, null); // null allows any country code
    if (!$phoneUtil->isValidNumber($phoneNumberObject)) {
        throw new Exception("Invalid phone number.");
    }
    $formattedPhone = $phoneUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
} catch (Exception $e) {
    sendResponse("error", 400, "Invalid phone number: " . $e->getMessage());
}

// Validate Password
$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/';
if (!preg_match($pattern, $password)) {
    sendResponse("error", 400, "Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one special character.");
}

// Check if the email already exists
$query = "SELECT USER_ID FROM USER WHERE EMAIL = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}
$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Query execution error: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    sendResponse("error", 409, "Email already registered.");
}

// Check if the phone number already exists
$query = "SELECT USER_ID FROM USER WHERE PHONE = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}
$stmt->bind_param("s", $formattedPhone);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Query execution error: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    sendResponse("error", 409, "Phone number already registered.");
}

// Generate OTP and expiry time (15 minutes from now)
$otp = rand(1000, 9999);
$expires = time() + 900; // 15 minutes

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
$query = "INSERT INTO USER (USER_NAME, EMAIL, PHONE, PASSWORD, OTP, EXPIRY_TIME) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}
$stmt->bind_param("ssssii", $name, $email, $formattedPhone, $hashedPassword, $otp, $expires);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Query execution error: " . $stmt->error);
}

$userId = $stmt->insert_id;

// ضبط الكوكيز بطريقة تدعم الموبايل والمتصفح
$cookie_expire = time() + (86400 * 30); // 30 يوم
$cookie_path = "/"; // متاحة لكل المسارات
$cookie_domain = ""; // ضع نطاق موقعك هنا إذا لزم الأمر
$cookie_secure = false; // يفضل جعله true إذا كان لديك HTTPS
$cookie_httponly = false; // يجب أن يكون false ليتمكن الموبايل من قراءتها
$cookie_samesite = "None"; // يضمن أن الكوكيز تُرسل مع جميع الطلبات (مهمة للموبايل)

// Set cookies (optional)
setcookie("EMAIL", $email, [
    'expires' => $cookie_expire,
    'path' => $cookie_path,
    'secure' => $cookie_secure,
    'httponly' => $cookie_httponly
]);

sendResponse("success", 200, "You have signed up successfully.", [
    "id" => $userId,
    "name" => $name,
    "email" => $email,
    "phone" => $formattedPhone
]);
?>