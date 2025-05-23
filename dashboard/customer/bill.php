<?php
session_start();

// Giả định bạn có data khách hàng lưu session hoặc từ DB
$store_name = "Quán Ngon ABC";
$store_phone = "0123456789";

$customer_name = $_SESSION['customer_name'] ?? 'Ngọc Sơn';
$customer_phone = $_SESSION['customer_phone'] ?? '0987654321';
$customer_address = $_SESSION['customer_address'] ?? '123 Đường Trường Chinh';

// Giỏ hàng lưu dạng mảng trong session, mỗi món gồm: img, name, price, qty
$cart = $_SESSION['cart'] ?? [
    [
        'img' => 'images/mon1.jpg',
        'name' => 'Phở Bò',
        'price' => 40000,
        'qty' => 2
    ],
    [
        'img' => 'images/mon2.jpg',
        'name' => 'Bún Chả',
        'price' => 35000,
        'qty' => 1
    ]
];

// Tính tổng tiền
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8" />
<title>Hóa đơn thanh toán</title>
<style>
    body { font-family: Arial, sans-serif; max-width: 700px; margin: 30px auto; }
    h2 { text-align: center; }
    .store-info, .customer-info { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    img { width: 60px; height: 60px; object-fit: cover; }
    tfoot td { font-weight: bold; }
</style>
</head>
<body>

<h2>HÓA ĐƠN THANH TOÁN</h2>

<div class="store-info">
    <strong>Tên quán:</strong> <?= htmlspecialchars($store_name) ?><br/>
    <strong>SĐT quán:</strong> <?= htmlspecialchars($store_phone) ?>
</div>

<div class="customer-info">
    <strong>Khách hàng:</strong> <?= htmlspecialchars($customer_name) ?><br/>
    <strong>SĐT khách:</strong> <?= htmlspecialchars($customer_phone) ?><br/>
    <strong>Địa chỉ:</strong> <?= htmlspecialchars($customer_address) ?>
</div>

<table>
    <thead>
        <tr>
            <th>Ảnh món</th>
            <th>Tên món</th>
            <th>Giá (₫)</th>
            <th>Số lượng</th>
            <th>Thành tiền (₫)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td><?= number_format($item['price'] * $item['qty']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align: right;">Tổng cộng:</td>
            <td><?= number_format($total) ?>₫</td>
        </tr>
    </tfoot>
</table>

</body>
</html>
