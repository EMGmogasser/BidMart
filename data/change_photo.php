<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Set the content type to JSON
header('Content-Type: application/json');

// Check if the 'HK' cookie is set
if (!isset($_COOKIE['HK'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'status_code' => 400,
        'message' => 'User not Authorized'
    ]);
    exit;
}

$user_id = $_COOKIE['HK'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'status_code' => 405,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

// Function to upload image
function uploadImage($file, $upload_dir = "../user_data/", $user_name) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => 'error', 'message' => 'File upload error.'];
    }

    // Allowed file types
    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/jpg", "image/webp"];
    if (!in_array($file['type'], $allowed_types)) {
        return ['status' => 'error', 'message' => 'Invalid file type. Only JPG, JPEG, PNG, and WebP allowed.'];
    }

    // Create directory if not exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate new unique filename
    $new_filename = uniqid("user_", true) . "_" . $user_name . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return ['status' => 'success', 'path' => $file_path];
    } else {
        return ['status' => 'error', 'message' => 'Failed to upload image.'];
    }
}

// Check if file is uploaded
if (!isset($_FILES['photo'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'status_code' => 400,
        'message' => 'No photo file uploaded.'
    ]);
    exit;
}

require '../includes/DB-con.php';

// Fetch username for file naming
$query = "SELECT USER_NAME FROM `USER` WHERE USER_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$USER_NAME = $user_data['USER_NAME'] ?? 'default_user';

// Upload the image
$upload_result = uploadImage($_FILES['photo'], "../user_data/", $USER_NAME);

if ($upload_result['status'] === 'error') {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'status_code' => 400,
        'message' => $upload_result['message']
    ]);
    exit;
}

$id_img = 'https://hk.herova.net/' . str_replace('../', '', $upload_result['path']);

// Update user photo in database
$sql = "UPDATE `USER` SET PHOTO = ? WHERE USER_ID = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'status_code' => 500,
        'message' => 'Failed to prepare SQL statement'
    ]);
    exit;
}

$stmt->bind_param('si', $id_img, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'status_code' => 200,
        'message' => 'User photo updated successfully.',
        'new_photo_url' => $id_img
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'status_code' => 400,
        'message' => 'Failed to update the user photo or no changes made.'
    ]);
}

$stmt->close();
$conn->close();
?>
