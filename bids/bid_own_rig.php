<?php
require '../includes/DB-con.php'; // Include database connection file

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: no-referrer');
// السماح بطلبات API من الموبايل والمتصفح
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed", "status_code" => 405]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid JSON format", "status_code" => 400]);
    exit;
}

$requiredFields = ['display_username', 'government_name', 'city_name', 'address', 'country'];
$missingFields = array_filter($requiredFields, fn($field) => empty($data[$field] ?? null));

if (!empty($missingFields)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields", "fields" => $missingFields, "status_code" => 400]);
    exit;
}

$username = htmlspecialchars(strip_tags($data['display_username']));
$government_name = htmlspecialchars(strip_tags($data['government_name']));
$city_name = htmlspecialchars(strip_tags($data['city_name']));
$address = htmlspecialchars(strip_tags($data['address']));
$country = htmlspecialchars(strip_tags($data['country']));
$USER_ID = htmlspecialchars(strip_tags($_COOKIE['HK'] ?? ''));
$SELLER_ID = htmlspecialchars(strip_tags($_COOKIE['HKH'] ?? ''));

if ($USER_ID === '') {
    http_response_code(403); // Forbidden
    echo json_encode([
        "status" => "error",
        "status_code" => 403,
        "message" => "User not registered as ordinary user to add product."
    ]);
    exit;
}

if ($SELLER_ID != '') {
    http_response_code(403); // Forbidden
    echo json_encode([
        "status" => "error",
        "status_code" => 403,
        "message" => "User already registered as a seller before."
    ]);
    exit;
}

try {
    // Check if the user already exists in the SELLER table
    $query = "SELECT SELLER_ID FROM SELLER WHERE USER_ID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Database query preparation error: " . $conn->error);
    }

    $stmt->bind_param("s", $USER_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists, so update the existing record
        $updateQuery = "UPDATE SELLER SET DISPLAY_USERNAME = ?, GOV = ?, CITY = ?, ADDRESS = ?, COUNTRY = ? WHERE USER_ID = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        if (!$stmtUpdate) {
            throw new Exception("Database update query preparation error: " . $conn->error);
        }

        $stmtUpdate->bind_param("ssssss", $username, $government_name, $city_name, $address, $country, $USER_ID);

        if (!$stmtUpdate->execute()) {
            throw new Exception("Failed to update bid owner: " . $stmtUpdate->error);
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Bid owner data updated successfully", "status_code" => 200]);
    } else {
        // User doesn't exist, insert a new record
        $insertQuery = "INSERT INTO SELLER (DISPLAY_USERNAME, GOV, CITY, ADDRESS, COUNTRY, USER_ID) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        if (!$stmtInsert) {
            throw new Exception("Database insertion error: " . $conn->error);
        }

        $stmtInsert->bind_param("ssssss", $username, $government_name, $city_name, $address, $country, $USER_ID);

        if (!$stmtInsert->execute()) {
            throw new Exception("Failed to register bid owner: " . $stmtInsert->error);
        }

        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Bid owner registered successfully", "id" => $stmtInsert->insert_id, "status_code" => 201]);
    }


    $cookie_expire = time() + (86400 * 30); // 30 يوم
    $cookie_path = "/"; // متاحة لكل المسارات
    $cookie_secure = false; // يفضل جعله true إذا كان لديك HTTPS
    $cookie_httponly = false; // يجب أن يكون false ليتمكن الموبايل من قراءتها

    setcookie("HK", $USER_ID, [
        'expires' => $cookie_expire,
        'path' => $cookie_path,
        'secure' => $cookie_secure,
        'httponly' => $cookie_httponly
    ]);

    setcookie("R2", 'not done', [
        'expires' => $cookie_expire,
        'path' => $cookie_path,
        'secure' => $cookie_secure,
        'httponly' => $cookie_httponly
    ]);



} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage(), "status_code" => 500]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($stmtUpdate)) $stmtUpdate->close();
    if (isset($stmtInsert)) $stmtInsert->close();
    $conn->close();
}
?>