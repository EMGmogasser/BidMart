<?php 

require '../includes/DB-con.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method is allowed"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON input"]);
    exit;
}

// Validate input
$errors = [];

if (!isset($input['BID_AMOUNT']) || !is_numeric($input['BID_AMOUNT']) || floatval($input['BID_AMOUNT']) <= 0) {
    $errors[] = 'Invalid bid amount.';
} else {
    $bidAmount = floatval($input['BID_AMOUNT']);
}

if (!isset($input['BIDDER_ID']) || !is_numeric($input['BIDDER_ID'])) {
    $errors[] = 'Invalid user ID.';
} else {
    $bidderId = intval($input['BIDDER_ID']);
}

if (!isset($input['I_ID']) || !is_numeric($input['I_ID'])) {
    $errors[] = 'Invalid item ID.';
} else {
    $itemId = intval($input['I_ID']);
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(["errors" => $errors]);
    exit;
}

// Check the current highest bid for the item
$stmt = $conn->prepare("SELECT MAX(BID_AMOUNT) as max_bid FROM BIDS WHERE I_ID = ?");
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$currentMax = floatval($row['max_bid'] ?? 0);
$stmt->close();

if ($bidAmount <= $currentMax) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Your bid must be higher than the current highest bid: $currentMax"
    ]);
    exit;
}

// Get the current timestamp
$bidTime = date("Y-m-d H:i:s");

// Check if the user has already bid on this item
$stmt = $conn->prepare("SELECT BID_ID FROM BIDS WHERE BIDDER_ID = ? AND I_ID = ?");
$stmt->bind_param("ii", $bidderId, $itemId);
$stmt->execute();
$result = $stmt->get_result();
$existingBid = $result->fetch_assoc();
$stmt->close();

if ($existingBid) {
    // Update existing bid
    $stmt = $conn->prepare("UPDATE BIDS SET BID_AMOUNT = ?, BID_TIME = ? WHERE BID_ID = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $bidId = $existingBid['BID_ID'];
    $stmt->bind_param("dsi", $bidAmount, $bidTime, $bidId);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Bid updated successfully",
            "bid_id" => $bidId
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Update failed: " . $stmt->error]);
    }

    $stmt->close();
} else {
    // Insert new bid
    $stmt = $conn->prepare("INSERT INTO BIDS (BID_AMOUNT, BID_TIME, BIDDER_ID, I_ID) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("dsii", $bidAmount, $bidTime, $bidderId, $itemId);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Bid placed successfully",
            "bid_id" => $stmt->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Insertion failed: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
