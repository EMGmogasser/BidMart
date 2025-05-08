<?php
require '../includes/DB-con.php'; // Database connection

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Function to send a JSON response and exit
function sendResponse($status, $status_code, $message, $extra_data = [])
{
    http_response_code($status_code);
    echo json_encode(array_merge(["status" => $status, "status_code" => $status_code, "message" => $message], $extra_data));
    exit;
}

try {
    // Get USER_ID from cookies securely
    $user_id = isset($_COOKIE['HK']) ? intval($_COOKIE['HK']) : null;
    if (!$user_id) {
        sendResponse("error", 401, "User not authenticated.");
    }

    // Retrieve input data
    $data = json_decode(file_get_contents("php://input"), true);
    $item_id = isset($data['I_ID']) ? intval($data['I_ID']) : null;
    $status = isset($data['STATUS']) ? trim(strtolower($data['STATUS'])) : null;
    $password = isset($data['PASSWORD']) ? trim($data['PASSWORD']) : null;

    // Validate required fields
    if (!$item_id || !$status || !$password) {
        sendResponse("error", 400, "Missing required fields.");
    }

    // Validate allowed status values
    $allowed_status = ['ended', 'canceled'];
    if (!in_array($status, $allowed_status)) {
        sendResponse("error", 400, "Invalid status. Must be 'ended' or 'canceled'.");
    }

    // Validate password format (at least 8 chars, one uppercase, one lowercase, one special char)
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/';
    if (!preg_match($pattern, $password)) {
        sendResponse("error", 400, "Invalid password format. Ensure it contains at least 8 characters, one uppercase, one lowercase, and one special character.");
    }

    // Retrieve hashed password from database
    $sql = "SELECT PASSWORD FROM USER WHERE USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse("error", 404, "User not found.");
    }

    $row = $result->fetch_assoc();
    $stored_password = $row['PASSWORD'];

    // Verify password
    if (!password_verify($password, $stored_password)) {
        sendResponse("error", 401, "Invalid password.");
    }

    // If status is 'canceled'
    if ($status === "canceled") {
        // جلب سعر المنتج
        $stmt = $conn->prepare("SELECT STARTING_PRICE FROM ITEM WHERE I_ID = ?");
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            sendResponse("error", 404, "Item not found.");
        }

        $item = $result->fetch_assoc();
        $starting_price = $item['STARTING_PRICE'];
        $penalty = $starting_price * 0.2; // حساب 20% من السعر

        // تحديث الرصيد في جدول USER
        $new_balance = $user['BALANCE'] - $penalty;
        $stmt = $conn->prepare("UPDATE USER SET BALANCE = ? WHERE USER_ID = ?");
        $stmt->bind_param("di", $new_balance, $user_id);
        if (!$stmt->execute()) {
            sendResponse("error", 500, "Failed to update user balance.");
        }

        // إضافة سجل جديد إلى جدول PAYMENTS
        $stmt = $conn->prepare("INSERT INTO PAYMENTS (REASON, USER_ID, DONE) VALUES ('Cancellation Fee', ?, ?)");
        $stmt->bind_param("id", $user_id, $penalty);
        if (!$stmt->execute()) {
            sendResponse("error", 500, "Failed to insert payment record.");
        }

        sendResponse("success", 200, "Item canceled. 20% penalty applied.");
    }


    // Update item status
    $update_sql = "UPDATE ITEM SET STATUS = ? WHERE I_ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $item_id);

    if ($update_stmt->execute()) {
        sendResponse("success", 200, "Item status updated successfully.");
    } else {
        sendResponse("error", 500, "Failed to update item status.");
    }

    // Close connections
    $stmt->close();
    $update_stmt->close();
    $conn->close();
} catch (Exception $e) {
    sendResponse("error", 500, "Server error", ["error_details" => $e->getMessage()]);
}
?>
