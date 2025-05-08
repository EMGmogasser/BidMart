<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// السماح بطلبات API من الموبايل والمتصفح
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


require '../vendor/autoload.php';
require '../includes/DB-con.php'; // Database connection
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json'); // Set response type to JSON

try {
    // Get user ID from cookie
    $user_id = isset($_COOKIE['HK']) ? $_COOKIE['HK'] : null;
    if (!$user_id) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "status_code" => 401,
            "message" => "User not authenticated."
        ]);
        exit;
    }

    // Fetch user phone number from "USER" table
    $sql = "SELECT PHONE FROM USER WHERE USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "status_code" => 404,
            "message" => "User not found.",
            "HK" => $user_id
        ]);
        exit;
    }

    $row = $result->fetch_assoc();
    $phone = $row['PHONE'];

    // Check if user is a registered seller
    $sql2 = "SELECT 1 FROM SELLER WHERE USER_ID = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("s", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows === 0) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "status_code" => 403,
            "message" => "User not authorized to add products."
        ]);
        exit;
    }

    // Generate a random 4-digit OTP
    $otp = rand(1000, 9999);

    $otp = strval($otp);
    // Function to send WhatsApp OTP
            
            function send_whatsapp($phone, $message, $otp) {
                $sid    = $_ENV['ACCOUNT_SID'];
                $token  = $_ENV['AUTH_TOKEN'];

                $twilio = new Twilio\Rest\Client($sid, $token);
            
                try {
                    $msg = $twilio->messages->create(
                        "whatsapp:" . $phone,
                        [
                            "from" => "whatsapp:+14155238886",
                            "body" => $message
                        ]
                    );
                    return $msg->sid; // إرجاع معرف الرسالة عند النجاح
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                    exit; // إرجاع تفاصيل الخطأ
                }
            }
            
            // التحقق من أن القيم موجودة
            if (!isset($otp) || !isset($phone)) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Missing OTP or phone number."]);
                exit;
            }
            
            // تحضير الرسالة
            $message = "🔐 *Your OTP Code* 🔐\n\nHello *Dear User*! \n\nYour secure OTP code is: \n\n *$otp* \n\n⚠️ *Do not share this code with anyone for security reasons.*\n\n *Peek Mart* - Shop Smart, Live Better! 🛒✨";
            
            // إرسال OTP
            $response = send_whatsapp($phone, $message, $otp);
            
            // ✅ تصحيح منطق التحقق
            if (strpos($response, "Error:") === 0) { // إذا كان هناك خطأ
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => $response]); // طباعة الخطأ الحقيقي
                exit;
            }

    // Update the OTP in the "SELLER" table
    $sql_update = "UPDATE SELLER SET OTP = ? WHERE USER_ID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $otp, $user_id);

    if (!$stmt_update->execute()) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "status_code" => 500,
            "message" => "Error updating OTP."
        ]);
        exit;
    }

    // ضبط الكوكيز بطريقة تدعم الموبايل والمتصفح
    $cookie_expire = time() + (86400 * 30); // 30 يوم
    $cookie_path = "/"; // متاحة لكل المسارات
    $cookie_secure = false; // يفضل جعله true إذا كان لديك HTTPS
    $cookie_httponly = false; // يجب أن يكون false ليتمكن الموبايل من قراءتها

    // Set secure cookie for phone number
    setcookie("PHONE", $phone, [
        'expires' => $cookie_expire,
        'path' => $cookie_path,
        'secure' => $cookie_secure,
        'httponly' => $cookie_httponly
    ]);

    // Success Response
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "status_code" => 200,
        "message" => "OTP sent and updated successfully.",
        "message_id" => $response
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "status_code" => 500,
        "message" => "Server error: " . $e->getMessage()
    ]);
}

// Close database connections safely
if (isset($stmt)) $stmt->close();
if (isset($stmt2)) $stmt2->close();
if (isset($stmt_update)) $stmt_update->close();
$conn->close();
?>
