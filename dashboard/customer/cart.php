<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu giỏ hàng cùng thông tin món ăn
$query = "
    SELECT c.item_id, c.quantity, m.name, m.price, m.image_path, m.type
    FROM cart c
    JOIN menu_items m ON c.item_id = m.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Tính tổng tiền
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link rel="stylesheet" href="../../assets/css/customer/cart.css?v=<?=time()?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-container">
        <h1>Giỏ hàng của bạn</h1>
        <a href="home.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
</header>

<main>
<?php if ($result->num_rows === 0): ?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <a href="menu.php" class="btn-continue">Tiếp tục mua hàng</a>
<?php else: ?>
    <form action="update_cart.php" method="post">
    <table class="cart-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên món</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()):
                $item_total = $row['price'] * $row['quantity'];
                $total_price += $item_total;
            ?>
            <tr>
                <td><img src="/DataStore/assets/images/<?= ($row['type'] === 'food' ? 'food' : 'drink') ?>/<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="80"></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 0, ',', '.') ?>₫</td>
                <td>
                    <input type="number" name="quantities[<?= $row['item_id'] ?>]" value="<?= $row['quantity'] ?>" min="1" style="width: 60px;">
                </td>
                <td><?= number_format($item_total, 0, ',', '.') ?>₫</td>
                <td>
                    <input type="checkbox" name="remove[]" value="<?= $row['item_id'] ?>" title="Xóa món này">
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="cart-total">
        <strong>Tổng tiền: </strong> <?= number_format($total_price, 0, ',', '.') ?>₫
    </div>

    <div class="cart-actions">
        <button type="submit" class="btn-update">Cập nhật giỏ hàng</button>
        <a href="bill.php" class="btn-bill">Thanh toán</a>
    </div>
    </form>
<?php endif; ?>
</main>

</body>
</html>
<?php $conn->close(); ?>
