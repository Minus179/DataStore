<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'store_owner' && $_SESSION['role'] != 'admin')) {
    header("Location: ../../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Chủ quán / Admin</title>
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
        <h2>Trang chủ - Chủ quán / Admin</h2>
        <p>Thông tin tổng quan về quán và các chức năng quản lý.</p>
        <ul>
            <li><a href="quan_ly_menu.php">Quản lý menu</a></li>
            <li><a href="don_hang.php">Danh sách đơn hàng</a></li>
            <li><a href="doanh_thu.php">Doanh thu</a></li>
            <li><a href="voucher.php">Quản lý voucher</a></li>
            <li><a href="thong_ke.php">Thống kê</a></li>
            <li><a href="thong_bao.php">Thông báo</a></li>
            <li><a href="bao_cao_nguoi_dung.php">Báo cáo người dùng</a></li>
        </ul>
    </main>
</body>
</html>
