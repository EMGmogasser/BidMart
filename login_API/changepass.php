<?php

require '../includes/DB-con.php'; // Include your database connection file

// Default response structure
$response = [
    'status' => 'error',
    'status_code' => 400,
    'message' => '',
    'data' => null
];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondWithError(405, 'Invalid request method. Only POST is allowed.');
}

// Get and validate the input
$input = getInputData();
$token = $input['token'];
$newPassword = $input['new_password'];

// Validate new password
validateNewPassword($newPassword);

// Check if the token exists and is valid
$userEmail = validateToken($token);

// Update the password in the database
updatePassword($userEmail, $newPassword);

$response['status'] = 'success';
$response['status_code'] = 200;
$response['message'] = 'Password updated successfully.';
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
    if (empty($input['token']) || empty($input['new_password'])) {
        respondWithError(400, 'Token and new password are required.');
    }
    return $input;
}

/**
 * Validate new password (length, complexity)
 */
function validateNewPassword($password) {
    // Password must be at least 8 characters long
    if (strlen($password) < 8) {
        respondWithError(400, 'Password must be at least 8 characters long.');
    }

    // Password must contain at least one uppercase letter, one lowercase letter, and one number
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        respondWithError(400, 'Password must contain at least one uppercase letter, one lowercase letter, and one number.');
    }
}

/**
 * Validate the token and check if it exists in the database
 * Returns the email associated with the token
 */
function validateToken($token) {
    global $conn;

    // Check if token exists and has not expired
    $query = "SELECT EMAIL, EXPIRY_TIME FROM PASSWORD_RESET WHERE TOKEN = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        respondWithError(400, 'Invalid token.');
    }

    $row = $result->fetch_assoc();
    if ($row['EXPIRY_TIME'] < time()) {
        respondWithError(400, 'Token has expired.');
    }

    // Return the user's email associated with the token
    return $row['EMAIL'];
}

/**
 * Update the user's password in the database
 */
function updatePassword($email, $newPassword) {
    global $conn;

    // Hash the new password before storing it
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the USER table
    $query = "UPDATE USER SET PASSWORD = ? WHERE EMAIL = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $hashedPassword, $email);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        respondWithError(500, 'Failed to update the password.');
    }

    // Optionally, delete the token after the password is updated to prevent reuse
    $deleteQuery = "DELETE FROM PASSWORD_RESET WHERE EMAIL = ?";
    $stmtDelete = $conn->prepare($deleteQuery);
    $stmtDelete->bind_param('s', $email);
    $stmtDelete->execute();
}