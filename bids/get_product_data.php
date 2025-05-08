<?php
require '../includes/DB-con.php'; // Include database connection

header('Content-Type: application/json');

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "status_code" => 400,
        "message" => "Invalid or missing product ID.",
        "data" => null
    ]);
    exit;
}

$product_id = (int) $_GET['id'];

// Query for product details and end date calculation
$query = "SELECT 
            I_ID, 
            ITEM_NAME, 
            STARTING_PRICE, 
            DESCRIPTION, 
            PHOTO, 
            START_DATE, 
            DATE_ADD(START_DATE, INTERVAL PERIOD_OF_BID DAY) AS END_DATE 
          FROM ITEM
          WHERE I_ID = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "status_code" => 500,
        "message" => "Database query failed: " . $conn->error,
        "data" => null
    ]);
    exit;
}

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // Construct the response
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "status_code" => 200,
        "message" => "Product retrieved successfully",
        "data" => [
            "I_ID" => $product["I_ID"],
            "ITEM_NAME" => $product["ITEM_NAME"],
            "STARTING_PRICE" => $product["STARTING_PRICE"],
            "DESCRIPTION" => $product["DESCRIPTION"],
            "PHOTO" => $product["PHOTO"],
            "START_DATE" => $product["START_DATE"],
            "END_DATE" => $product["END_DATE"]
        ]
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "status_code" => 404,
        "message" => "Product not found",
        "data" => []
    ]);
}

$stmt->close();
$conn->close();
?>