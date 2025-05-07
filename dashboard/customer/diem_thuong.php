<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

$user_id = $_SESSION['user_id'];
$query = "SELECT points FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tích điểm đổi quà</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Tích điểm đổi quà</h2>

        <p>Số điểm hiện tại của bạn: <?php echo $user['points']; ?> điểm</p>
        <p>Bạn có thể đổi quà từ số điểm tích lũy của mình!</p>

        <form action="redeem_points.php" method="POST">
            <input type="number" name="points_to_redeem" placeholder="Số điểm muốn đổi" min="1" required>
            <button type="submit">Đổi điểm</button>
        </form>
    </main>
</body>
</html>
