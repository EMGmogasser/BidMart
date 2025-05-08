<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Check if the 'HK' cookie is set
if (!isset($_COOKIE['HK'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => 'error',
        'status_code' => 400,
        'message' => 'User not Authorized'
    ]);
    exit;
}

// Get the user ID from the cookie
$user_id = $_COOKIE['HK'];

// Connect to the database
require '../includes/DB-con.php';

// Get pagination parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 12;
$offset = ($page - 1) * $limit;

// Get total items count
$count_sql = "SELECT COUNT(*) as total FROM `ITEM` WHERE POST_BY = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param('i', $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);
$count_stmt->close();

// Query to fetch paginated items
$sql = "SELECT 
            I_ID,
            ITEM_NAME,
            DESCRIPTION,
            PHOTO,
            STARTING_PRICE,
            EXPECTED_PRICE,
            START_DATE,
            PERIOD_OF_BID,
            STATUS,
            CAT_ID,
            POST_BY,
            created_at
        FROM `ITEM`
        WHERE POST_BY = ?
        LIMIT ? OFFSET ?";

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'status_code' => 500,
        'message' => 'Failed to prepare SQL statement'
    ]);
    exit;
}

// Bind parameters and execute the query
$stmt->bind_param('iii', $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any items for the user
if ($result->num_rows > 0) {
    // Fetch all the items
    $items = $result->fetch_all(MYSQLI_ASSOC);

    // Return success response with pagination info
    echo json_encode([
        'status' => 'success',
        'status_code' => 200,
        'data' => $items,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'limit_per_page' => $limit
        ]
    ]);
} else {
    http_response_code(404); // Not Found
    echo json_encode([
        'status' => 'error',
        'status_code' => 404,
        'message' => 'No items found for this user.',
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'limit_per_page' => $limit
        ]
    ]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
