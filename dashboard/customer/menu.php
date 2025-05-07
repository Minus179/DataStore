<?php
// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

// Lấy ID món ăn từ URL
if (!isset($_GET['id'])) {
    echo "Không tìm thấy món ăn!";
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM menu WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Món ăn không tồn tại!";
    exit;
}

$item = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $item['name']; ?> - Chi tiết món ăn</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #fff7f3;
            padding: 30px;
            color: #333;
        }

        .food-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .food-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .food-details {
            padding: 20px;
        }

        .food-details h1 {
            font-size: 28px;
            color: #ff5722;
        }

        .food-details p {
            font-size: 16px;
            margin: 10px 0;
        }

        .price {
            font-weight: bold;
            font-size: 20px;
            color: #d84315;
        }

        .back {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            background-color: #ff5722;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        .back:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
    <div class="food-container">
        <img src="../../assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
        <div class="food-details">
            <h1><?php echo $item['name']; ?></h1>
            <p><?php echo $item['description']; ?></p>
            <p class="price">Giá: <?php echo number_format($item['price'], 0, ',', '.') . ' đ'; ?></p>
            <a href="timkiem.php" class="back">← Quay lại tìm kiếm</a>
        </div>
    </div>
</body>
</html>
