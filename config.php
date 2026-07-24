<?php
// config.php - إعدادات الاتصال بقاعدة البيانات
$host = 'localhost'; // غيّر عند الاستضافة
$dbname = 'car_parts';
$username = 'root';
$password = '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    die('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ثوابت الموقع
define('SITE_NAME', 'كار بارتس');
define('SITE_PHONE', '+249 123 456 789');
define('SITE_EMAIL', 'info@carparts.com');
define('SITE_ADDRESS', 'ود مدني، السودان');
define('SITE_LOGO', 'images/logo.png');
?>