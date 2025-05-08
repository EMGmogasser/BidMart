<?php
// Set response type
header('Content-Type: application/json');
require_once '../vendor/autoload.php';
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

// Check if the 'HK' cookie is set
if (!isset($_COOKIE['HK'])) {
    sendResponse("error", 400, "User not authorized.");
}

// Get user ID from the cookie
$user_id = $_COOKIE['HK'];

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse("error", 405, "Method not allowed. Use POST.");
}

// Function to send a JSON response
function sendResponse($status, $status_code, $message) {
    http_response_code($status_code);
    echo json_encode([
        'status' => $status,
        'status_code' => $status_code,
        'message' => $message
    ]);
    exit;
}

// Establish database connection
require '../includes/DB-con.php';
if (!$conn) {
    sendResponse("error", 500, "Database connection failed.");
}

// Handle phone number update
if (isset($_GET['key']) && intval($_GET['key']) === 1) {
    if (empty($_POST['PHONE']) || empty($_POST['OLD_PHONE'])) {
        sendResponse("error", 400, "Phone number and old phone are required fields.");
    }

    $phone = trim($_POST['PHONE']);
    $old_phone = trim($_POST['OLD_PHONE']);

    if ($phone === $old_phone) {
        sendResponse("error", 400, "Phone number cannot be the old one.");
    }

    if (preg_match('/\s/', $phone)) {
        sendResponse("error", 400, "Phone number cannot contain whitespace.");
    }

    $phoneUtil = PhoneNumberUtil::getInstance();
    try {
        $phoneNumberObject = $phoneUtil->parse($phone, null);
        if (!$phoneUtil->isValidNumber($phoneNumberObject)) {
            throw new Exception("Invalid phone number.");
        }
        $formattedPhone = $phoneUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
    } catch (Exception $e) {
        sendResponse("error", 400, "Invalid phone number: " . $e->getMessage());
    }

    $check_sql = "SELECT PHONE FROM `USER` WHERE PHONE = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $old_phone);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        sendResponse("error", 400, "Old phone number not found.");
    }
    $check_stmt->close();

    $sql = "UPDATE `USER` SET PHONE = ? WHERE PHONE = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        sendResponse("error", 500, "Failed to prepare SQL statement.");
    }

    $stmt->bind_param('ss', $phone, $old_phone);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        sendResponse("success", 200, "User data updated successfully.");
    } else {
        sendResponse("error", 400, "No changes were made or user not found.");
    }

    $stmt->close();
    $conn->close();
}


// Validate that password is provided
if (!isset($_POST['PASSWORD'])) {
    sendResponse("error", 400, "Password is required for verification.");
}

$password = trim($_POST['PASSWORD']);


// Handle user data update
if (!isset($_POST['USER_NAME'], $_POST['EMAIL'], $_POST['PHONE'])) {
    sendResponse("error", 400, "Missing required fields.");
}

$user_name = trim($_POST['USER_NAME']);
$email = trim($_POST['EMAIL']);
$phone = trim($_POST['PHONE']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse("error", 400, "Invalid email format.");
}

if (preg_match('/\s/', $phone)) {
    sendResponse("error", 400, "Phone number cannot contain whitespace.");
}

$sql = "UPDATE `USER` SET USER_NAME = ?, EMAIL = ?, PHONE = ? WHERE USER_ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    sendResponse("error", 500, "Failed to prepare SQL statement.");
}

$stmt->bind_param('sssi', $user_name, $email, $phone, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    sendResponse("success", 200, "User data updated successfully.");
} else {
    sendResponse("error", 400, "No changes were made or user not found.");
}

$stmt->close();
$conn->close();