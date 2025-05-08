<?php

header('Content-Type: application/json');

// Include database connection
require_once '../includes/DB-con.php';

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if (!isset($_GET['userId'])) {
    echo json_encode(["error" => "User ID is required"]);
    exit;
}

$userId = intval($_GET['userId']);
$sql = "SELECT SELLER_ACTIVATION FROM SELLER WHERE USER_ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    echo json_encode(["error" => "Query execution failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$row = $result->fetch_assoc();
$isActivated = $row['SELLER_ACTIVATION'] == 1;

echo json_encode(["admin_activation" => $isActivated]);

$stmt->close();
$conn->close();

?>
