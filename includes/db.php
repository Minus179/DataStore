<?php
// Thông tin kết nối
$host     = 'localhost';
$db       = 'datastore_food';
$user     = 'root';
$pass     = '';
$charset  = 'utf8mb4';

// ------------------ Kết nối bằng PDO ------------------
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $pdo_options);
} catch (PDOException $e) {
    die('Kết nối PDO thất bại: ' . $e->getMessage());
}

// ------------------ Kết nối bằng MySQLi ------------------
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Kết nối MySQLi thất bại: " . mysqli_connect_error());
}
?>
