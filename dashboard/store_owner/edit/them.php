<?php
require_once __DIR__ . '/../../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $type = $_POST['type'] ?? 'food';
    $description = $_POST['description'] ?? '';
    $available = $_POST['available'] ?? 1;

    // Xử lý ảnh chính
    if (isset($_FILES['image_main']) && $_FILES['image_main']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image_main']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $ext;
        $target_path = '../../../assets/images/' . $type . '/' . $image_name;
        
        if (move_uploaded_file($_FILES['image_main']['tmp_name'], $target_path)) {
            // Lưu vào database
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, price, type, description, image_path) 
                            VALUES (:name, :price, :type, :description, :image_path)");
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':type' => $type,
            ':description' => $description,
            ':image_path' => $image_name
        ]);


            header('Location: ../quan_ly_menu.php');
            exit();
        } else {
            die('Lỗi khi lưu ảnh chính.');
        }
    } else {
        die('Vui lòng chọn ảnh chính.');
    }
} else {
    header('Location: ../quan_ly_menu.php');
    exit();
}
