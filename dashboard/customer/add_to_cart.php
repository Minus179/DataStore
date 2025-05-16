<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý POST thêm món
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'], $_POST['csrf_token'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Token không hợp lệ. Vui lòng tải lại trang.");
    }

    $item_id = intval($_POST['item_id']);
    $quantity = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $query = "SELECT * FROM menu_items WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();
            $_SESSION['cart'][$item_id] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $quantity
            ];
        }
    }

    header("Location: cart.php");
    exit();
}

// Lấy danh sách món hiển thị demo (bạn thay bằng query thực tế)
$items = [
    ['id' => 1, 'name' => 'Món ăn 1', 'price' => 50000, 'image' => 'assets/images/sample-item.jpg', 'description' => 'Mô tả món ăn 1'],
    ['id' => 2, 'name' => 'Món ăn 2', 'price' => 60000, 'image' => 'assets/images/sample-item2.jpg', 'description' => 'Mô tả món ăn 2'],
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Thêm món vào giỏ hàng</title>
<style>
    /* Giữ nguyên CSS bạn có */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0; padding: 0;
    }
    header {
        background-color: #e67e22;
        color: #fff;
        text-align: center;
        padding: 10px;
    }
    h1 { margin: 0; }
    a {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        background-color: #d35400;
        border-radius: 5px;
    }
    .container {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .product {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px;
        background-color: #fafafa;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .product img {
        width: 100px; height: 100px; object-fit: cover; border-radius: 5px;
    }
    .product-info {
        margin-left: 20px;
    }
    .product-info h2 {
        margin: 0; font-size: 18px; color: #333;
    }
    .product-info p {
        margin: 5px 0; color: #777;
    }
    .product-info .price {
        font-size: 20px; font-weight: bold; color: #e74c3c;
    }
    .button {
        padding: 10px 15px;
        background-color: #e67e22;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .button:hover {
        background-color: #d35400;
    }
</style>
</head>
<body>

<header>
    <h1>Thêm món vào giỏ hàng</h1>
</header>

<div class="container">
    <?php foreach($items as $item): ?>
        <div class="product">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
            <div class="product-info">
                <h2><?= htmlspecialchars($item['name']) ?></h2>
                <p><?= htmlspecialchars($item['description']) ?></p>
                <p class="price"><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                <form method="POST" action="">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>" />
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <button class="button" type="submit">Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    <a href="cart.php">Xem giỏ hàng</a>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
