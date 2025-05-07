<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của bạn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Đơn hàng của bạn</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <li>
                        Đơn hàng #<?php echo $order['id']; ?> - Trạng thái: <?php echo $order['status']; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Không có đơn hàng nào.</p>
        <?php endif; ?>
    </main>
</body>
</html>
