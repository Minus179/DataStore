<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM cart WHERE user_id = '$user_id' AND status = 'pending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Thanh toán</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php 
                    $total = 0;
                    while ($item = mysqli_fetch_assoc($result)): 
                        $total += $item['price'] * $item['quantity'];
                ?>
                    <li>
                        <?php echo $item['product_name']; ?> - <?php echo $item['quantity']; ?> x <?php echo $item['price']; ?> đ
                    </li>
                <?php endwhile; ?>
            </ul>

            <h3>Tổng cộng: <?php echo $total; ?> đ</h3>

            <form action="process_payment.php" method="POST">
                <input type="text" name="payment_method" placeholder="Phương thức thanh toán" required>
                <button type="submit">Thanh toán</button>
            </form>
        <?php else: ?>
            <p>Không có đơn hàng nào trong giỏ hàng của bạn.</p>
        <?php endif; ?>
    </main>
</body>
</html>
