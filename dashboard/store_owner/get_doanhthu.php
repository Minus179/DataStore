<?php
$conn = new mysqli("localhost", "root", "", "datastore"); // nhớ thay "datastore" bằng đúng tên CSDL của bạn
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$date = $_GET['date'] ?? date('Y-m-d');
$stmt = $mysqli->prepare("SELECT SUM(tong_tien) as total FROM donhang WHERE DATE(ngay_dat) = ?");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total = $row['total'] ?? 0;

$total = $sampleData[$date] ?? 0;

echo "<table border='1' cellpadding='8'>
        <tr><th>Ngày</th><th>Tổng Tiền (VND)</th></tr>
        <tr><td>$date</td><td>" . number_format($total, 0, ',', '.') . " VND</td></tr>
      </table>";
