<?php
// تفعيل عرض الأخطاء أثناء التطوير
error_reporting(E_ALL);
ini_set('display_errors', 1);

// تسجيل الأخطاء في ملف (للاختبار فقط)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// تضمين الاتصال بقاعدة البيانات
require_once '../includes/DB-con.php';

// ضبط الهيدر لاستجابة JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// إعداد الكوكيز
$cookie_expire = time() + (86400 * 30); // 30 يوم
$cookie_path = "/";
$cookie_secure = false;
$cookie_httponly = false;
$cookie_samesite = "None";

// دالة إرسال استجابة JSON
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

// دالة لمسح الكوكيز
function clearCookies() {
    global $cookie_path, $cookie_secure, $cookie_httponly;
    foreach (["HK", "USER_NAME", "HKH", "HKHN", "HKHM", "EMAIL"] as $cookie) {
        setcookie($cookie, "", time() - 3600, $cookie_path, "", $cookie_secure, $cookie_httponly);
        unset($_COOKIE[$cookie]);
    }
}

// استلام البيانات من JSON
$input = json_decode(file_get_contents('php://input'), true);

// التحقق من القيم المطلوبة
if (empty($input['email']) || empty($input['password'])) {
    sendResponse("error", 400, "Email and password are required.");
}

$email = trim($input['email']);
$password = trim($input['password']);

// التحقق من صحة الإيميل
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse("error", 400, "Invalid email format.");
}

// التحقق من صيغة كلمة المرور
$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/';
if (!preg_match($pattern, $password)) {
    sendResponse("error", 400, "Wrong password format. Please recheck it.");
}

// استعلام جلب بيانات المستخدم
$query = "SELECT USER_ID, EMAIL_ACTIVATION, USER_NAME, EMAIL, PASSWORD, PHONE FROM USER WHERE EMAIL = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    sendResponse("error", 500, "Database query failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    clearCookies();
    sendResponse("error", 403, "Invalid email or password.");
}

$user = $result->fetch_assoc();

// التحقق من حالة تفعيل الحساب
if ($user['EMAIL_ACTIVATION'] == 0) {
    clearCookies();
    if (!empty($user['EMAIL'])) {
        setcookie("EMAIL", $user['EMAIL'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
    }
    sendResponse("error", 403, "This account has not been activated yet!");
}

// التحقق من كلمة المرور
if (!password_verify($password, $user['PASSWORD'])) {
    clearCookies();
    sendResponse("error", 403, "Invalid email or password.");
}

// استعلام التحقق من حالة البائع
$query2 = "SELECT SELLER_ID, SELLER_ACTIVATION, DISPLAY_USERNAME, IBAN, OTP_ACTIVATION FROM SELLER WHERE USER_ID = ?";
$stmt2 = $conn->prepare($query2);

if (!$stmt2) {
    sendResponse("error", 500, "Database query failed: " . $conn->error);
}

$stmt2->bind_param("i", $user['USER_ID']);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    $seller = $result2->fetch_assoc();
    if ($seller['SELLER_ACTIVATION'] == 1) {
        setcookie("HKH", $seller['SELLER_ID'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
    } else {
        if ($seller['OTP_ACTIVATION'] == 0){
            if (!empty($seller['IBAN'])) {
                setcookie("PHONE", $user['PHONE'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
            } else {
                setcookie("HKHN", $seller['SELLER_ID'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
            }
        } else {
            setcookie("HKH", $seller['SELLER_ID'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
        }
    }
} else {
    setcookie("HKHM", 'NOT REG', time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
}

// ضبط الكوكيز عند تسجيل الدخول بنجاح
setcookie("HK", $user['USER_ID'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);
setcookie("USER_NAME", $user['USER_NAME'], time() + 86400, $cookie_path, "", $cookie_secure, $cookie_httponly);

// إرسال استجابة نجاح
sendResponse("success", 200, "Login successful", [
    "USER_ID" => $user['USER_ID'],
    "USER_NAME" => $user['USER_NAME'],
    "EMAIL" => $user['EMAIL']
]);

// إغلاق الاتصال بقاعدة البيانات
$stmt->close();
$stmt2->close();
$conn->close();
?>
