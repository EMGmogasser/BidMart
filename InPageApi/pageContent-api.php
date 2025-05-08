<?php
// Include database connection
require_once '../includes/DB-con.php';

// Set content type for JSON response
header('Content-Type: application/json');

// Get current page and limit from request (defaults to page 1, limit 12)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;

// Validate page and limit
if ($page <= 0 || $limit <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "status" => "error",
      	"status_code" => 400,
        "message" => "Invalid page or limit value"
    ]);
    exit;
}

// Calculate offset
$offset = ($page - 1) * $limit;

try {
    // Query to get total record count
    $totalQuery = "SELECT COUNT(*) as total FROM PAGE_CONTENT";
    $totalResult = $conn->query($totalQuery);
    if (!$totalResult) {
        throw new Exception("Failed to fetch total records");
    }
    $totalRow = $totalResult->fetch_assoc();
    $totalRecords = intval($totalRow['total']);

    // Calculate total pages
    $totalPages = ceil($totalRecords / $limit);

    // Query to fetch paginated products
    $query = "SELECT ID, SECTION_NAME, CONTENT, IMAGE, SUB_TITLE, SUB_CONTENT, SUB_HEAD FROM PAGE_CONTENT LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare query");
    }
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch all products
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Success response
        http_response_code(200); // OK
        echo json_encode([
            "status" => "success",
          	"status_code" => 200,
            "message" => "Products retrieved successfully",
            "data" => $products,
            "pagination" => [
                "current_page" => $page,
                "total_pages" => $totalPages,
                "total_records" => $totalRecords,
                "limit_per_page" => $limit
            ]
        ]);
    } else {
        // No products found on this page
        http_response_code(200); // OK (no data is not an error)
        echo json_encode([
            "status" => "success",
          	"status_code" => 200,
            "message" => "No products found for the requested page",
            "data" => [],
            "pagination" => [
                "current_page" => $page,
                "total_pages" => $totalPages,
                "total_records" => $totalRecords,
                "limit_per_page" => $limit
            ]
        ]);
    }
} catch (Exception $e) {
    // Internal server error
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "status" => "error",
      	"status_code" => 500,
        "message" => "Internal server error: " . $e->getMessage()
    ]);
}
?>