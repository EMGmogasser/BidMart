<?php
// Include database connection
require_once '../includes/DB-con.php';

// Include PHPMailer for sending emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Set content type for JSON response
header('Content-Type: application/json');

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

// Validate if key is provided and correct
if (!isset($input['key']) || $input['key'] !== 'SM') {
    sendResponse("error", 400, "Invalid or missing key.");
}

// Retrieve email from cookies
if (!isset($_COOKIE['EMAIL']) || empty(trim($_COOKIE['EMAIL']))) {
    sendResponse("error", 400, "Email is required and cannot be blank.");
}

$email = trim($_COOKIE['EMAIL']);

// Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/\s/', $email)) {
    sendResponse("error", 400, "Invalid email format. Email cannot contain whitespace.");
}
if (!preg_match("/^[a-zA-Z]/", $email)) {
    sendResponse("error", 400, "Email cannot start with a number or symbol and must start with a letter.");
}

// Check if the email exists in the database
$query = "SELECT USER_ID, USER_NAME FROM USER WHERE EMAIL = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}
$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Query execution error: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    sendResponse("error", 404, "Email not registered.");
}
$user = $result->fetch_assoc();

// Generate OTP and expiry time (15 minutes from now)
$otp = rand(1000, 9999);
$expires = time() + 900; // 15 minutes

// Update OTP and expiry time in the database
$updateQuery = "UPDATE USER SET OTP = ?, EXPIRY_TIME = ? WHERE EMAIL = ?";
$stmt = $conn->prepare($updateQuery);
if (!$stmt) {
    sendResponse("error", 500, "Database error: " . $conn->error);
}
$stmt->bind_param("iis", $otp, $expires, $email);
if (!$stmt->execute()) {
    sendResponse("error", 500, "Failed to update OTP in the database.");
}

// Send OTP via email
$mail = new PHPMailer(true);
try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'emailserver@meetingmeals.sa';
    $mail->Password = 'Pn8aoe0$';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Optional: Enable SMTP debugging (use 2 for client and server messages)
    // $mail->SMTPDebug = 2;

    // Email settings
    $appName = "PeekMart";
    $mail->setFrom('emailserver@meetingmeals.sa', '=?UTF-8?B?' . base64_encode($appName) . '?=');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Your OTP Code";
    $mail->Body = "<p style='font-family: Arial, sans-serif; font-size: 16px; color: #333;'>
                    Dear {$user['USER_NAME']},<br><br>
                    Your OTP code is: 
                    <strong style='font-size: 18px; padding: 10px; border: 2px solid #4CAF50; border-radius: 5px; background-color: #f1f1f1; color: #4CAF50; display: inline-block;'>
                        $otp
                    </strong><br><br>
                    Please use this code to complete your action. The code is valid for 15 minutes.<br><br>
                    Thank you for using $appName.<br><br>
                    Best regards,<br>The $appName Team
                </p>";
    $mail->CharSet = 'UTF-8';

    if ($mail->send()) {
        sendResponse("success", 200, "OTP sent successfully.", ["email" => $email]);
    } else {
        sendResponse("error", 500, "Failed to send email. Please try again later.");
    }
} catch (Exception $e) {
    error_log("Mail Error: " . $mail->ErrorInfo);
    sendResponse("error", 500, "Failed to send email: " . $mail->ErrorInfo);
}
?>