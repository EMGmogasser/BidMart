<?php
header('Content-Type: application/json');

require '../includes/DB-con.php'; // Include your database connection file
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Default response structure
$response = [
    'status' => 'error',
    'status_code' => 500,
    'message' => '',
];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondWithError(405, 'Invalid request method. Only POST is allowed.');
}

// Get and validate the input
$input = getInputData();
$email = validateEmail($input['email']);

// Check if the email exists in the database
checkEmailExistence($email);

// Generate a reset token and insert into the database
$token = generateResetToken();
$expires = time() + 1800; // 30 minutes expiration time
insertPasswordResetToken($email, $token, $expires);

// Send the reset link via email
$resetLink = "https://hk.herova.net/reset_password.php?token=$token";
sendResetEmail($email, $resetLink);

// Success response
$response['status'] = 'success';
$response['status_code'] = 200;
$response['message'] = 'Password reset email sent successfully.';
http_response_code(200); // OK
echo json_encode($response);

/**
 * Respond with an error message and appropriate HTTP code
 */
function respondWithError($code, $message) {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'status_code' => $code, 'message' => $message]);
    exit;
}

/**
 * Get and decode JSON input data
 */
function getInputData() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['email'])) {
        respondWithError(400, 'Email is required.');
    }
    return $input;
}

/**
 * Validate the email format
 */
function validateEmail($email) {
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        respondWithError(400, 'Invalid email format.');
    }
    return $email;
}

/**
 * Check if the email exists in the USER table
 */
function checkEmailExistence($email) {
    global $conn;

    $query = "SELECT * FROM USER WHERE EMAIL = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        respondWithError(404, 'The provided email does not exist in our system.');
    }
}

/**
 * Generate a unique password reset token
 */
function generateResetToken() {
    return bin2hex(random_bytes(50));
}

/**
 * Insert the reset token into the PASSWORD_RESET table
 */
function insertPasswordResetToken($email, $token, $expires) {
    global $conn;

    $query = "INSERT INTO PASSWORD_RESET (EMAIL, TOKEN, EXPIRY_TIME) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $email, $token, $expires);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        respondWithError(500, 'Failed to generate password reset token.');
    }
}

/**
 * Send the password reset email
 */
function sendResetEmail($email, $resetLink) {
    $subject = "Reset your password";
    $me = "PeekMart";

    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'emailserver@meetingmeals.sa';
        $mail->Password = 'Pn8aoe0$'; // Replace with actual password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Set sender and recipient
        $mail->setFrom('emailserver@meetingmeals.sa', '=?UTF-8?B?' . base64_encode($me) . '?=');
        $mail->addAddress($email);

        // Set email format
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <p style='font-family: Arial, sans-serif; font-size: 16px; color: #333;'>
                Dear user,<br><br>
                Click the following link to reset your password:<br><br>
                <a href='$resetLink' style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; color: #8B322C; 
                    display: inline-block; padding: 10px 20px; margin: 0; text-decoration: underline; text-decoration-color: #8B322C;'>
                    $resetLink
                </a><br><br>
                Thank you for using $me.<br><br>
                Best regards!
            </p>";
        $mail->CharSet = 'UTF-8';

        $mail->send();
    } catch (Exception $e) {
        respondWithError(500, "Failed to send email: {$mail->ErrorInfo}");
    }
}
?>