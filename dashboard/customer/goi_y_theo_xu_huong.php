<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

$query = "SELECT * FROM menu WHERE trend = 1 ORDER BY RAND() LIMIT 5"; // Lọc các món ăn theo xu hướng
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gợi ý món ăn theo xu hướng</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Gợi ý món ăn theo xu hướng</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <?php echo $item['name']; ?> - <?php echo $item['price']; ?> đ
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Chúng tôi không có món ăn theo xu hướng cho bạn ngay lúc này.</p>
        <?php endif; ?>
    </main>
</body>
</html>
