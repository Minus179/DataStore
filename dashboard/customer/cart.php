<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập và là khách hàng
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tính tổng tiền trong giỏ hàng
$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

// Cập nhật số lượng món ăn trong giỏ (nếu có)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $item_id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$item_id]);
        } else {
            $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        }
    }

    // Chuyển hướng lại trang giỏ hàng sau khi cập nhật
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #e67e22;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        h1 {
            margin: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #e67e22;
            margin-top: 20px;
        }

        .button {
            padding: 10px 15px;
            background-color: #e67e22;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #d35400;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Giỏ hàng của bạn</h1>
</header>

<div class="container">
    <form method="POST" action="cart.php">
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Tên món</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item) : ?>
                        <tr>
                            <td><img src="<?= $item['image']; ?>" alt="<?= $item['name']; ?>" style="width: 50px;"><?= $item['name']; ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.'); ?>₫</td>
                            <td><input type="number" name="quantity[<?= $item['id']; ?>]" value="<?= $item['quantity']; ?>" min="1"></td>
                            <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-price">
                Tổng cộng: <?= number_format($total_price, 0, ',', '.'); ?>₫
            </div>
            <button class="button" type="submit" name="update_cart">Cập nhật giỏ hàng</button>
            <a href="checkout.php" class="button">Tiến hành thanh toán</a>
        <?php else : ?>
            <p>Giỏ hàng của bạn hiện tại chưa có món nào.</p>
        <?php endif; ?>
    </form>
</div>

<footer>
    <p>&copy; 2025 Store Name. All rights reserved.</p>
</footer>

</body>
</html>
