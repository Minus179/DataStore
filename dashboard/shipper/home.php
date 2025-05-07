<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'shipper') {
    header("Location: ../../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Shipper</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .back-btn {
            display: inline-block;
            margin: 10px;
            padding: 8px 16px;
            background-color: #eee;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Trang chủ - Shipper</h2>
        <p>Thông tin đơn hàng và các chức năng liên quan đến shipper.</p>
        <ul>
            <li><a href="don_hang.php">Danh sách đơn hàng</a></li>
            <li><a href="vi_tri_hien_tai.php">Vị trí hiện tại</a></li>
            <li><a href="lich_su_giao.php">Lịch sử giao hàng</a></li>
            <li><a href="thong_bao.php">Thông báo</a></li>
        </ul>
    </main>
</body>
</html>
