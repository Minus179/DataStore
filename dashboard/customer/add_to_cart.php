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

// Kiểm tra nếu có món được thêm vào giỏ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $quantity = 1;  // Mỗi lần thêm sẽ mặc định là 1 món

    // Kiểm tra nếu giỏ hàng chưa tồn tại trong session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Kiểm tra nếu món ăn đã tồn tại trong giỏ hàng, nếu có thì tăng số lượng
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Lấy thông tin món ăn từ cơ sở dữ liệu
        $query = "SELECT * FROM menu_items WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();
            $_SESSION['cart'][$item_id] = array(
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $quantity
            );
        }
    }

    // Chuyển hướng về trang giỏ hàng
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm món vào giỏ hàng</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fafafa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .product-info {
            margin-left: 20px;
        }

        .product-info h2 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .product-info p {
            margin: 5px 0;
            color: #777;
        }

        .product-info .price {
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
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
    <h1>Thêm món vào giỏ hàng</h1>
</header>

<div class="container">
    <div class="product">
        <img src="assets/images/sample-item.jpg" alt="Món ăn">
        <div class="product-info">
            <h2>Món ăn ví dụ</h2>
            <p>Đây là mô tả ngắn về món ăn này.</p>
            <p class="price">50,000₫</p>
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="item_id" value="1">
                <button class="button" type="submit">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
    <a href="cart.php">Xem giỏ hàng</a>
</div>

<footer>
    <p>&copy; 2025 Store Name. All rights reserved.</p>
</footer>

</body>
</html>
