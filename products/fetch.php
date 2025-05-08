<?php

require_once '../includes/DB-con.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($_GET['key'])) {
    sendResponse(400, "Key parameter is missing");
}

$key = (int) $_GET['key'];

$actions = [
    1 => 'fetchCurrentWorkingBids',
    2 => 'fetchCategories',
    3 => 'fetchEndedBids',
    4 => 'fetchSoonBids'
];

if (array_key_exists($key, $actions)) {
    call_user_func($actions[$key], $conn, $input);
} else {
    sendResponse(400, "Invalid key parameter");
}

$conn->close();

function fetchCurrentWorkingBids($conn, $input) {
    $query = "SELECT 
                  I.I_ID, 
                  I.ITEM_NAME, 
                  I.DESCRIPTION, 
                  I.PHOTO,                   
                  I.STARTING_PRICE, 
                  I.START_DATE, 
                  DATE_ADD(I.START_DATE, INTERVAL I.PERIOD_OF_BID DAY) AS END_DATE, 
                  I.STATUS, 
                  COALESCE(B.MAX_BID, 0) AS MAX_BID_AMOUNT
              FROM ITEM I
              LEFT JOIN (
                  SELECT I_ID, MAX(BID_AMOUNT) AS MAX_BID
                  FROM BIDS
                  GROUP BY I_ID
              ) B ON I.I_ID = B.I_ID
              WHERE I.START_DATE < ? 
              AND I.STATUS = ?
              ORDER BY RAND() 
              LIMIT ? OFFSET ?";

    $countQuery = "SELECT COUNT(*) as total FROM ITEM WHERE START_DATE < ? AND STATUS = ?";

    executeQueryWithPagination($conn, $query, $countQuery, [date('Y-m-d'), 'not_ended'], "Current working bids", $input);
}

function fetchCategories($conn) {
    $query = "SELECT CAT_ID, CAT_NAME, IMG FROM CATEGORY";
    executeQuery($conn, $query, [], "Categories retrieved successfully");
}

function fetchEndedBids($conn, $input) {
    $query = "SELECT 
                  I.I_ID, 
                  I.ITEM_NAME, 
                  I.DESCRIPTION, 
                  I.PHOTO, 
                  I.START_DATE, 
                  I.STARTING_PRICE,
                  I.STATUS, 
                  COALESCE(B.MAX_BID, 0) AS MAX_BID_AMOUNT
              FROM ITEM I
              LEFT JOIN (
                  SELECT I_ID, MAX(BID_AMOUNT) AS MAX_BID
                  FROM BIDS
                  GROUP BY I_ID
              ) B ON I.I_ID = B.I_ID
              WHERE I.STATUS = ?
              ORDER BY RAND() 
              LIMIT ? OFFSET ?";

    $countQuery = "SELECT COUNT(*) as total FROM ITEM WHERE STATUS = ?";

    executeQueryWithPagination($conn, $query, $countQuery, ['ended'], "Ended bids", $input);
}

function fetchSoonBids($conn, $input) {
    $query = "SELECT I_ID, ITEM_NAME, DESCRIPTION, STARTING_PRICE, PHOTO, START_DATE, 
                     DATE_ADD(START_DATE, INTERVAL PERIOD_OF_BID DAY) AS END_DATE, STATUS 
              FROM ITEM 
              WHERE START_DATE >= ? AND STATUS = ? 
              ORDER BY RAND() 
              LIMIT ? OFFSET ?";

    $countQuery = "SELECT COUNT(*) as total FROM ITEM WHERE START_DATE >= ? AND STATUS = ?";

    executeQueryWithPagination($conn, $query, $countQuery, [date('Y-m-d'), 'not_ended'], "Upcoming products", $input);
}

function executeQueryWithPagination($conn, $query, $countQuery, $params, $successMessage, $input) {
    $page = isset($input['page']) && is_numeric($input['page']) && $input['page'] > 0 ? (int)$input['page'] : 1;
    $limit = isset($input['limit']) && is_numeric($input['limit']) && $input['limit'] > 0 ? (int)$input['limit'] : 12;
    $offset = ($page - 1) * $limit;

    // حساب العدد الكلي للسجلات
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalRecords = $result->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $limit);

    // استعلام البيانات
    $params[] = $limit;
    $params[] = $offset;

    executeQuery($conn, $query, $params, $successMessage, [
        "current_page" => $page,
        "total_pages" => $totalPages,
        "total_records" => $totalRecords,
        "limit_per_page" => $limit
    ]);
}

function executeQuery($conn, $query, $params, $successMessage, $pagination = null) {
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $types = str_repeat('s', count($params) - 2) . 'ii';
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        sendResponse(200, $successMessage, $data, $pagination);
    } else {
        sendResponse(404, "No records found", [], $pagination);
    }
}

function sendResponse($code, $message, $data = null, $pagination = null) {
    http_response_code($code);
    $response = [
        "status" => $code === 200 ? "success" : "error",
        "code" => $code,
        "message" => $message,
        "data" => $data
    ];

    if ($pagination) {
        $response["pagination"] = $pagination;
    }

    echo json_encode($response);
    exit;
}
