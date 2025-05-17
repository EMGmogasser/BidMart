<?php
require_once('../vendor/autoload.php');

use GuzzleHttp\Client;

$client = new Client();
// تحميل ملف .env
$dotenv = Dotenv\Dotenv::createImmutable('../'); // تأكد من أن المسار صحيح
$dotenv->load();

// إعداد المتغيرات من ملف .env
$secretKey = $_ENV['TAP_SECRET_KEY'];

$tap_id = isset($_GET['tap_id']) ? htmlspecialchars($_GET['tap_id']) : '';

if (!empty($tap_id)) {
    try {
        $response = $client->request('GET', 'https://api.tap.company/v2/charges/' . $tap_id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $secretKey,
                'accept' => 'application/json',
            ],
        ]);

        echo $response->getBody();
    } catch (Exception $e) {
        echo 'حدث خطأ أثناء استدعاء Tap API: ' . $e->getMessage();
    }
} else {
    echo 'لم يتم توفير tap_id في الرابط.';
}
