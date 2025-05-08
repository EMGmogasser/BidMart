<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// Set the content type to JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests
header("Access-Control-Allow-Credentials: true");

// Check if HK exists in cookies or POST
$user_id = isset($_COOKIE['HK']) ? $_COOKIE['HK'] : (isset($_POST['HK']) ? $_POST['HK'] : null);

if (!$user_id || !is_numeric($user_id)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => 'error',
        'status_code' => 400,
        'message' => 'Invalid or missing user ID'
    ]);
    exit;
}

require '../includes/DB-con.php'; // Include database connection


// Query to retrieve user + seller data in one go
$sql = "
    SELECT 
        u.USER_NAME, 
        u.EMAIL, 
        u.PHONE, 
        u.PHOTO,
        u.BALANCE,
        u.LOYALITY_P,

        s.SELLER_ID,
        s.SELLER_ACTIVATION,
        s.DISPLAY_USERNAME,
        s.COUNTRY,
        s.GOV,
        s.CITY,
        s.ADDRESS,
        s.FULL_ID_NAME,
        s.ID_NO,
        s.ID_PHOTO,
        s.BANK_NAME,
        s.IBAN,
        s.LOYALTY_POINTS
    FROM USER u
    LEFT JOIN SELLER s ON u.USER_ID = s.USER_ID
    WHERE u.USER_ID = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'status_code' => 500,
        'message' => 'Failed to prepare SQL statement'
    ]);
    exit;
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // قم بتجهيز مصفوفة أساسية للمستخدم
    $user = [
        'USER_NAME'      => $row['USER_NAME'],
        'EMAIL'          => $row['EMAIL'],
        'PHONE'          => $row['PHONE'],
        'PHOTO'          => $row['PHOTO'],
        'BALANCE'        => $row['BALANCE'],
        'LOYALITY_P'     => $row['LOYALITY_P'],
    ];

    // التحقق إن كان المستخدم يملك سجل بائع
    if (!is_null($row['SELLER_ID'])) {
        // يوجد سجل بائع
        // تحقق من حالة التفعيل
        if ($row['SELLER_ACTIVATION'] == 1) {
            $user['seller_info'] = [
                'SELLER_ID'         => $row['SELLER_ID'],
                'SELLER_ACTIVATION' => $row['SELLER_ACTIVATION'],
                'DISPLAY_USERNAME'  => $row['DISPLAY_USERNAME'],
                'COUNTRY'           => $row['COUNTRY'],
                'GOV'               => $row['GOV'],
                'CITY'              => $row['CITY'],
                'ADDRESS'           => $row['ADDRESS'],
                'FULL_ID_NAME'      => $row['FULL_ID_NAME'],
                'ID_NO'             => $row['ID_NO'],
                'ID_PHOTO'          => $row['ID_PHOTO'],
                'BANK_NAME'         => $row['BANK_NAME'],
                'IBAN'              => $row['IBAN'],
                'LOYALTY_POINTS'    => $row['LOYALTY_POINTS']
            ];
        } else {
            $user['seller_info'] = 'Not activated yet.';
        }
    } else {
        // لا يوجد سجل بائع للمستخدم
        $user['seller_info'] = 'Ordinary user.';
    }

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'status_code' => 200,
        'data' => $user
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'status_code' => 404,
        'message' => 'User not found'
    ]);
}

$stmt->close();
$conn->close();
?>
