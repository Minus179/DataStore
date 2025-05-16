<?php
include("../database/connection.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $image_path = $_POST['image_path'];
    $description = $_POST['description'];
    $popularity = $_POST['popularity'];
    $description_image = $_POST['description_image'];
    $quantity = $_POST['quantity'];
    $additional_info = $_POST['additional_info'];

    $sql = "UPDATE items SET name=?, price=?, type=?, category=?, image_path=?, description=?, popularity=?, description_image=?, quantity=?, additional_info=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssssisii", $name, $price, $type, $category, $image_path, $description, $popularity, $description_image, $quantity, $additional_info, $id);

    if ($stmt->execute()) {
        header("Location: quan_ly_menu.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa món</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Sửa Thông Tin Món</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <label>Tên món:</label>
        <input type="text" name="name" value="<?= $item['name'] ?>" required><br>

        <label>Giá:</label>
        <input type="number" name="price" value="<?= $item['price'] ?>" required><br>

        <label>Loại:</label>
        <input type="text" name="type" value="<?= $item['type'] ?>" required><br>

        <label>Phân loại:</label>
        <input type="text" name="category" value="<?= $item['category'] ?>" required><br>

        <label>Đường dẫn ảnh chính:</label>
        <input type="text" name="image_path" value="<?= $item['image_path'] ?>"><br>

        <label>Mô tả:</label>
        <textarea name="description" required><?= $item['description'] ?></textarea><br>

        <label>Độ phổ biến:</label>
        <input type="number" name="popularity" value="<?= $item['popularity'] ?>" min="0" max="5"><br>

        <label>Ảnh mô tả:</label>
        <input type="text" name="description_image" value="<?= $item['description_image'] ?>"><br>

        <label>Số lượng:</label>
        <input type="number" name="quantity" value="<?= $item['quantity'] ?>"><br>

        <label>Thông tin thêm:</label>
        <textarea name="additional_info"><?= $item['additional_info'] ?></textarea><br>

        <button type="submit" name="update">Cập nhật</button>
    </form>
</body>
</html>