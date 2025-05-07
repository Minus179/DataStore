<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: ../../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin quán - Chủ quán</title>
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
        <h2>Thông tin quán</h2>
        <p>Thông tin quán của bạn hiển thị tại đây.</p>
    </main>
</body>
</html>
