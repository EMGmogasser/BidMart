<?php
require '../vendor/autoload.php';
require '../includes/DB-con.php'; // Database connection

header('Content-Type: application/json'); // Set response type to JSON
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$response = ["status" => "error", "status_code" => 500, "message" => "Server error"];

try {
    // Get user ID from cookie
    $user_id = $_COOKIE['HK'] ?? null;

    if (!$user_id) {
        http_response_code(401);
        $response = [
            "status" => "error",
            "status_code" => 401,
            "message" => "User not authenticated."
        ];
        echo json_encode($response);
        exit;
    }

    // Get OTP from request body
    $data = json_decode(file_get_contents("php://input"), true);
    $user_otp = $data['otp'] ?? null;

    if (!$user_otp) {
        http_response_code(400);
        $response = [
            "status" => "error",
            "status_code" => 400,
            "message" => "OTP is required."
        ];
        echo json_encode($response);
        exit;
    }

    // Check OTP in the "SELLER" table
    $sql = "SELECT OTP, SELLER_ID FROM SELLER WHERE USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        $response = [
            "status" => "error",
            "status_code" => 404,
            "message" => "User not found."
        ];
    } else {
        $row = $result->fetch_assoc();
        $stored_otp = $row['OTP'];

        if ($user_otp == $stored_otp) {
            // OTP matches, authentication successful
            http_response_code(200);
            $response = [
                "status" => "success",
                "status_code" => 200,
                "message" => "OTP verified successfully."
            ];

            // ضبط الكوكيز بطريقة تدعم الموبايل والمتصفح
            $cookie_expire = time() + (86400 * 30); // 30 يوم
            $cookie_path = "/"; // متاحة لكل المسارات
            $cookie_secure = false; // يفضل جعله true إذا كان لديك HTTPS
            $cookie_httponly = false; // يجب أن يكون false ليتمكن الموبايل من قراءتها

            if (isset($_COOKIE["PHONE"])) {
                setcookie("PHONE", "", time() - 3600, "/"); // حذف الكوكيز
            }

            setcookie("HKH", $row['SELLER_ID'], [
                'expires' => $cookie_expire,
                'path' => $cookie_path,
                'secure' => $cookie_secure,
                'httponly' => $cookie_httponly
            ]);

            setcookie("HK", $user_id, [
                'expires' => $cookie_expire,
                'path' => $cookie_path,
                'secure' => $cookie_secure,
                'httponly' => $cookie_httponly
            ]);

            //Update OTP_ACTIVATION to 1
            $seller_id = $row['SELLER_ID'];

            $update_sql = "UPDATE SELLER SET OTP_ACTIVATION = 1 WHERE SELLER_ID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $seller_id);
            $update_stmt->execute();
            $update_stmt->close();

        } else {
            http_response_code(401);
            $response = [
                "status" => "error",
                "status_code" => 401,
                "message" => "Invalid OTP."
            ];
        }
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    $response = [
        "status" => "error",
        "status_code" => 500,
        "message" => "Server error",
        "error_details" => $e->getMessage() // لإظهار الخطأ أثناء التطوير فقط
    ];
}

$conn->close();
echo json_encode($response);
?>
