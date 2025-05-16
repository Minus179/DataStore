<?php
// Kết nối database
include '../../includes/db_connect.php';

// Xử lý khi form được gửi
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy dữ liệu từ form một cách an toàn
    $name = $_POST["name"] ?? '';
    $price = $_POST["price"] ?? 0;
    $type = $_POST["type"] ?? '';
    $description = $_POST["description"] ?? '';
    $additional_info = $_POST["additional_info"] ?? '';
    $quantity = $_POST["quantity"] ?? 0;

    // Xử lý upload ảnh
    $image_path = '';

    if (isset($_FILES["image_path"]) && $_FILES["image_path"]["error"] === 0) {
        $file = $_FILES["image_path"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $newFileName = uniqid("img_") . "." . $ext;
            $uploadPath = "../../../assets/images/" . $newFileName;

            if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
                $image_path = "assets/images/" . $newFileName;
            } else {
                echo "❌ Lỗi khi tải ảnh lên!";
                exit;
            }
        } else {
            echo "❌ Ảnh món không hợp lệ! Chỉ chấp nhận JPG, PNG, GIF, WEBP.";
            exit;
        }
    } else {
        echo "❌ Bạn chưa chọn ảnh hoặc ảnh lỗi!";
        exit;
    }

    // Thêm dữ liệu vào database (dùng prepared statement để tránh SQL Injection)
    $stmt = $conn->prepare("INSERT INTO products (name, price, type, description, additional_info, quantity, image_path)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssis", $name, $price, $type, $description, $additional_info, $quantity, $image_path);

    if ($stmt->execute()) {
        echo "✅ Thêm món thành công!";
        // Redirect về trang quản lý menu (nếu cần)
        header("Location: ../quan_ly_menu.php");
        exit;
    } else {
        echo "❌ Lỗi khi thêm món: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML FORM ĐỂ GỬI DỮ LIỆU -->
<form method="POST" enctype="multipart/form-data">
    <label>Tên món:</label>
    <input type="text" name="name" required><br>

    <label>Giá:</label>
    <input type="number" name="price" step="0.01" required><br>

    <label>Loại:</label>
    <select name="type" required>
        <option value="food">Món ăn</option>
        <option value="drink">Đồ uống</option>
    </select><br>

    <label>Mô tả:</label>
    <textarea name="description"></textarea><br>

    <label>Thông tin thêm:</label>
    <textarea name="additional_info"></textarea><br>

    <label>Số lượng:</label>
    <input type="number" name="quantity" required><br>

    <label>Ảnh món:</label>
    <input type="file" name="image_path" accept=".jpg,.jpeg,.png,.gif,.webp" required><br>

    <button type="submit">Thêm món</button>
</form>
