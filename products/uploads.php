<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


header("Content-Type: application/json"); // تحديد نوع الاستجابة JSON

if (!function_exists('responseError')) {
    function responseError($message, $code = 400) {
        http_response_code($code);
        echo json_encode(["status" => "error", "status_code" => $code, "message" => $message]);
        exit;
    }
}


// دالة رفع الصورة محليًا

if (!function_exists('uploadImageLocally')) {

function uploadImageLocally($file, $path = '') {
    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/jpg", "image/webp"];
    $maxFileSize = 15 * 1024 * 1024; // 15MB

    if (!in_array($file["type"], $allowedTypes)) {
        return ["error" => "Invalid file type. Only jpg, jpeg, png, webp, and gif are allowed."];
    }

    if ($file["size"] > $maxFileSize) {
        return ["error" => "File size exceeds the limit (15MB)."];
    }

    $uploadDir = "uploads/" . $path;
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ["error" => "Failed to create upload directory."];
        }
    }

    $fileName = uniqid() . "_" . basename($file["name"]);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        return ["file_path" => 'https://hk.herova.net/' . $filePath];
    } else {
        return ["error" => "Failed to move uploaded file."];
    }
}
  
}

// التأكد من أن الطلب هو POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responseError("Invalid request method. Use POST.", 405);
}

// التحقق من أن الملف قد تم تحميله
if (!isset($_FILES['photo']) || empty($_FILES['photo']['name'])) {
    responseError("No image file uploaded. Make sure the form uses 'enctype=multipart/form-data' and the input name is 'photo'.", 400);
}

// استخراج المجلد المستهدف من الطلب إذا تم تحديده
$bath = $_POST['path'] ?? ''; // استخدام path كمتغير يتم تمريره من الطلب
$allowed_folders = ['personal_pic/', 'users/', ''];

if (!in_array($bath, $allowed_folders)) {
    responseError("Invalid upload directory.", 400);
}

// رفع الصورة
$photo_upload = uploadImageLocally($_FILES['photo'], $bath);
if (isset($photo_upload["error"])) {
    responseError("Image upload failed: " . $photo_upload["error"], 500);
}

// استجابة ناجحة
http_response_code(200);
echo json_encode([
    "status" => "success",
    "status_code" => 200,
    "message" => "File uploaded successfully.",
    "file_path" => $photo_upload["file_path"]
]);

?>
