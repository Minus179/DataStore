// add_item.php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: ../../login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ten_csdl");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $targetDir = "../../assets/images/";
    $imageName = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;
    $imagePath = "assets/images/" . $imageName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false || !in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $message = "❌ File không hợp lệ.";
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO menu_items (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $imagePath);
        if ($stmt->execute()) {
            $message = "✅ Thêm món thành công!";
        } else {
            $message = "❌ Lỗi khi lưu vào CSDL.";
        }
        $stmt->close();
    } elseif ($uploadOk) {
        $message = "❌ Tải ảnh thất bại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm món</title>
</head>
<body>
    <h2>Thêm món mới</h2>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Tên món: <input type="text" name="name" required></label><br><br>
        <label>Giá: <input type="number" name="price" step="0.01" required></label><br><br>
        <label>Hình ảnh: <input type="file" name="image" accept="image/*" required></label><br><br>
        <button type="submit">Thêm món</button>
    </form>
</body>
</html>
