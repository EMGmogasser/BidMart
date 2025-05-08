<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // عدلها حسب الحاجة
header('Access-Control-Allow-Credentials: true');

if (isset($_COOKIE["PHONE"])) {
    echo json_encode(["phone" => $_COOKIE["PHONE"]]);
} else {
    echo json_encode(["error" => "Phone number not found"]);
}
?>