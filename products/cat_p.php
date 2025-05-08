<?php

require_once '../includes/DB-con.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // جلب page و limit من GET والتحقق من صحتها
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 48;
    
    if ($page < 1) $page = 1;
    if ($limit < 1 || $limit > 100) $limit = 48; // وضع حد أعلى لـ limit

    $offset = ($page - 1) * $limit;

    // جلب categoryId من GET مع الحماية
    $categoryId = isset($_GET['id']) ? intval($_GET['id']) : null;

    // استعلام لجلب عدد السجلات الكلي
    $countQuery = "SELECT COUNT(*) as total_records FROM ITEM I WHERE I.STATUS = 'not_ended'";
    $countParams = [];
    $countTypes = '';

    if ($categoryId !== null) {
        $countQuery .= " AND I.CAT_ID = ?";
        $countParams[] = $categoryId;
        $countTypes .= "i";
    }

    $stmt = $conn->prepare($countQuery);
    if (!$stmt) {
        throw new Exception("Database query preparation failed.");
    }
    
    if (!empty($countParams)) {
        $stmt->bind_param($countTypes, ...$countParams);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $totalRecords = $result->fetch_assoc()['total_records'];
    $totalPages = ceil($totalRecords / $limit);

    // استعلام لجلب المنتجات مع الباجينيشن
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
              WHERE I.STATUS = 'not_ended'";

    $params = [];
    $types = '';

    if ($categoryId !== null) {
        $query .= " AND I.CAT_ID = ?";
        $params[] = $categoryId;
        $types .= "i";
    }

    $query .= " ORDER BY RAND() LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Database query preparation failed.");
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    sendResponse(200, "Products retrieved successfully", $data, $page, $totalPages, $totalRecords, $limit);

} catch (Exception $e) {
    sendResponse(500, "Server error: " . $e->getMessage(), []);
}

$conn->close();

function sendResponse($code, $message, $data = null, $currentPage = null, $totalPages = null, $totalRecords = null, $limitPerPage = null) {
    http_response_code($code);
    $response = [
        "status" => $code === 200 ? "success" : "error",
        "code" => $code,
        "message" => $message,
        "data" => $data
    ];

    if ($code === 200) {
        $response["pagination"] = [
            "current_page" => $currentPage,
            "total_pages" => $totalPages,
            "total_records" => $totalRecords,
            "limit_per_page" => $limitPerPage
        ];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
