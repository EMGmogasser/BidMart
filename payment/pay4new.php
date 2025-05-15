<?php
require_once('../vendor/autoload.php'); // تأكد من أن autoload.php موجود في مسار vendor

use GuzzleHttp\Client;

$client = new Client();
// تحميل ملف .env
$dotenv = Dotenv\Dotenv::createImmutable('../'); // تأكد من أن المسار صحيح
$dotenv->load();

// إعداد المتغيرات من ملف .env
$secretKey = $_ENV['TAP_SECRET_KEY'];
// $merchantId = $_ENV['MERCHANT_ID'];

// بيانات الدفع
$endpoint = 'https://api.tap.company/v2/charges/';
$currency = 'USD';
$description = 'Order Payment';
$redirectUrl = 'https://hk.herova.net/reciet.php';

// الحصول على القيم من GET
$amount = isset($_GET['price']) ? floatval($_GET['price']) : 0;
$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? 'mohammadgasser0203@gmail.com'; // تصحيح البريد الإلكتروني
$phone = $_GET['phone'] ?? '1020836270';

// تنسيق رقم الهاتف
$phone = preg_replace('/\D/', '', ltrim($phone, '0')); // إزالة الأصفار الأولية وأي رموز غير رقمية
$countryCode = 20; // تأكد من صحة كود الدولة

// إعداد بيانات العميل
$customerData = ['first_name' => $name];
if (!empty($email)) {
    $customerData['email'] = $email;
}
if (!empty($phone)) {
    $customerData['phone'] = ['country_code' => $countryCode, 'number' => $phone];
}

// إعداد بيانات الطلب
$requestBody = [
    'amount' => $amount,
    'currency' => $currency,
    'description' => $description,
    'customer' => $customerData,
    // 'merchant' => ['id' => $merchantId],
    'source' => ['id' => 'src_all'],
    'customer_initiated' => true,
    'save_card' => false,
    'receipt' => ['email' => true, 'sms' => true],
    'redirect' => ['url' => $redirectUrl],
];

try {
    // إرسال الطلب
    $response = $client->post($endpoint, [
        'json' => $requestBody,
        'headers' => [
            'Authorization' => 'Bearer ' . $secretKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
    ]);

    // معالجة الاستجابة
    $responseData = json_decode($response->getBody(), true);
    if (isset($responseData['transaction']['url'])) {
        header('Location: ' . $responseData['transaction']['url']);
        exit;
    } else {
        echo 'Payment successful, but no redirect URL was provided.';
    }
} catch (\GuzzleHttp\Exception\RequestException $e) {
    if ($e->hasResponse()) {
        echo 'Status Code: ' . $e->getResponse()->getStatusCode() . "<br>";
        echo 'Response Body: ' . $e->getResponse()->getBody();
    } else {
        echo 'Request error: ' . $e->getMessage();
    }
}
?>
