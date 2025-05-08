<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

function sendResponse($status, $message, $data = []) {
    http_response_code($status);
    echo json_encode(["status" => $status === 200 ? "success" : "error", "status_code" => $status, "message" => $message, "data" => $data]);
    exit;
}

// **📌 تضمين الاتصال بقاعدة البيانات**
require_once '../includes/DB-con.php';

// التحقق من نوع الطلب
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(405, "Invalid request method. Use POST.");
}

// التحقق من وجود الكوكي
if (!isset($_COOKIE['HK'])) {
    sendResponse(401, "Unauthorized. USER_ID cookie is missing.");
}

if (!isset($_COOKIE['USER_NAME'])) {
    sendResponse(401, "Unauthorized. You must go login again.");
}

$USER_ID = $_COOKIE['HK'];
$USER_NAME = $_COOKIE['USER_NAME'];
$full_name = $_POST['full_name'] ?? '';
$id_number = $_POST['id_number'] ?? '';
$iban = $_POST['iban'] ?? '';
$id_img = "";
$iban_img = "";

// 🔹 وظيفة رفع الصور محليًا
function uploadImage($file, $upload_dir = "user_data/", $user_name) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        sendResponse(400, "File upload error.");
    }
    
    // التحقق من نوع الملف
    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/jpg", "image/webp"];
    if (!in_array($file['type'], $allowed_types)) {
        sendResponse(400, "Invalid file type. Only JPG, JPEG, and PNG allowed.");
    }
    
    // إنشاء المجلد إذا لم يكن موجودًا
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $new_filename = uniqid("user_", true) . "_" . $user_name . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return $file_path;
    } else {
        sendResponse(500, "Failed to upload image.");
    }
}

// رفع الصور إن وجدت
if (!empty($_FILES['id_img']['name'])) {
    $id_img = uploadImage($_FILES['id_img'], "bids/user_data/", $USER_NAME);
  	$id_img = 'https://hk.herova.net/' . $id_img ;
}

if (!empty($_FILES['iban_img']['name'])) {
    $iban_img = uploadImage($_FILES['iban_img'], "user_data/", $USER_NAME);
  	$iban_img = 'https://hk.herova.net/' . $iban_img ;
}

// تحديث بيانات المستخدم في قاعدة البيانات
$stmt = $conn->prepare("UPDATE SELLER SET FULL_ID_NAME = ?, ID_NO = ?, IBAN = ?, ID_PHOTO = ?, IBAN_PHOTO = ? WHERE user_id = ?");
$stmt->bind_param("sssssi", $full_name, $id_number, $iban, $id_img, $iban_img, $USER_ID);

if ($stmt->execute()) {
    sendResponse(200, "User data updated successfully.", ['fullName'=> $full_name, 'id_number'=> $id_number, 'iban'=> $iban, 'id_img'=> $id_img, 'iban_img'=> $iban_img]);
} else {
    sendResponse(500, "Failed to update user data.");
}

$stmt->close();
$conn->close();

?>
