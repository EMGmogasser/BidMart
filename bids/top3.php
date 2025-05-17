<?php
require '../includes/DB-con.php'; // Include database connection

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Enable CORS if needed

// تحقق من صحة الـ ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "status_code" => 400,
        "message" => "Invalid or missing product ID (id).",
        "data" => []
    ]);
    exit;
}

$I_ID = (int) $_GET['id']; // تحويل إلى عدد صحيح
$user = (int) $_GET['uid']; // الحصول على معرف المستخدم من الكوكيز
if (!$user) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "status_code" => 401,
        "message" => "User not authenticated.",
        "data" => []
    ]);
    exit;
}


// تحقق من وجوده في PAYMENTS
$checkStmt = $conn->prepare("SELECT user_id FROM Enrollments WHERE user_id = ? AND product_id = ? LIMIT 1");
if (!$checkStmt) {
    http_response_code(500);
    echo json_encode(["status"=> "error", "error" => "Prepare check failed Enrollments: " . $conn->error]);
    exit;
}
$checkStmt->bind_param("ii", $user, $I_ID);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $stat = true;
}else {
    $stat = false;
}

$checkStmt->close();

// **1️⃣ استعلام للحصول على أعلى 3 مزايدين**
$query_top_bidders = "
    SELECT U.USER_NAME, U.PHOTO, B.BIDDER_ID, B.BID_AMOUNT, B.I_ID 
    FROM BIDS B 
    INNER JOIN USER U ON B.BIDDER_ID = U.USER_ID 
    WHERE B.I_ID = ? 
    ORDER BY B.BID_AMOUNT DESC 
    LIMIT 3
";

// **تنفيذ الاستعلام الأول لجلب أعلى 3 مزايدين**
$stmt_top = $conn->prepare($query_top_bidders);
if (!$stmt_top) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "status_code" => 500,
        "message" => "Database query preparation failed: " . $conn->error,
        "data" => []
    ]);
    exit;
}
$stmt_top->bind_param("i", $I_ID);
$stmt_top->execute();
$result_top = $stmt_top->get_result();

// **2️⃣ استعلام للحصول على العدد الكلي للمزايدين**
$query_total_bidders = "
    SELECT COUNT(BIDDER_ID) AS total_bidders
    FROM BIDS
    WHERE I_ID = ?
";



// **تنفيذ الاستعلام الثاني لحساب العدد الكلي للمزايدين**
$stmt_total = $conn->prepare($query_total_bidders);
$stmt_total->bind_param("i", $I_ID);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_bidders = $result_total->fetch_assoc()['total_bidders'] ?? 0;





// **2️⃣ استعلام للحصول على العدد الكلي للمزايدين**
$query_total_enrolled = "
    SELECT COUNT(user_id) AS total_bidders
    FROM Enrollments
    WHERE product_id = ?
";



// **تنفيذ الاستعلام الثاني لحساب العدد الكلي للمزايدين**
$stmt_total = $conn->prepare($query_total_enrolled);
$stmt_total->bind_param("i", $I_ID);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_enrolled = $result_total->fetch_assoc()['total_bidders'] ?? 0;

// **تحضير البيانات للإرجاع**
$bidders_data = [];
if ($result_top->num_rows > 0) {
    $bidders_data = $result_top->fetch_all(MYSQLI_ASSOC);
}

// **إرجاع البيانات بصيغة JSON**
http_response_code(200);
echo json_encode([
    "status" => "success",
    "status_code" => 200,
    "message" => "Bidders retrieved successfully",
    "total_bidders" => $total_bidders,  // العدد الكلي للمزايدين
    "total_enrolled" => $total_enrolled,  // العدد الكلي للمسجلين
    "data" => $bidders_data,      // بيانات أعلى 3 مزايدين
    "user_status" => $stat        // حالة المستخدم (مزايد أم لا)
]);

// **إغلاق الاتصال**
$stmt_top->close();
$stmt_total->close();
$conn->close();
?>
