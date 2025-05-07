<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

$query = "SELECT * FROM menu ORDER BY RAND() LIMIT 5"; // Gợi ý 5 món ngẫu nhiên từ menu
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gợi ý món ăn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Gợi ý món ăn cho bạn</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <?php echo $item['name']; ?> - <?php echo $item['price']; ?> đ
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Chúng tôi không có gợi ý món ăn cho bạn ngay lúc này.</p>
        <?php endif; ?>
    </main>
</body>
</html>
