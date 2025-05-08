<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// التحقق من وجود الكوكيز
if (!empty($_COOKIE)) {
    echo json_encode(
        $_COOKIE // جميع الكوكيز الموجودة
    );
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No cookies found"
    ]);
}
?>
