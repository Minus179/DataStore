<?php
// Thông tin kết nối cơ sở dữ liệu
$host = 'localhost';  
$username = 'root';   
$password = '';       
$dbname = 'datastore_food'; 

// Tạo kết nối
$conn = mysqli_connect($host, $username, $password, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
