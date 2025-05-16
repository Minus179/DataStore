<?php
session_start();

// Kiểm tra đăng nhập & phân quyền
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner'])) {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $price       = intval($_POST['price']);
    $description = trim($_POST['description']);
    $type        = $_POST['type'];
    $category    = $_POST['category'];

    // Validate dữ liệu đơn giản
    if (empty($name)) $errors[] = 'Tên món không được để trống.';
    if ($price <= 0) $errors[] = 'Giá phải lớn hơn 0.';
    if (!in_array($type, ['food', 'drink'])) $errors[] = 'Loại món không hợp lệ.';
    if (!in_array($category, ['food', 'drink'])) $errors[] = 'Danh mục không hợp lệ.';

    // Thư mục lưu ảnh
    $mainDir   = __DIR__ . '/../../assets/images/' . $type . '/';
    $detailDir = __DIR__ . '/../../assets/images/detail/';

    if (!is_dir($mainDir)) mkdir($mainDir, 0755, true);
    if (!is_dir($detailDir)) mkdir($detailDir, 0755, true);

    $mainImage = $_FILES['image_path'];
    $descImage = $_FILES['description_image'];

    if ($mainImage['error'] === UPLOAD_ERR_OK) {
        $mainName = time() . '_' . basename($mainImage['name']);
        if (!move_uploaded_file($mainImage['tmp_name'], $mainDir . $mainName)) {
            $errors[] = 'Lỗi khi lưu ảnh chính.';
        }
    } else {
        $errors[] = 'Vui lòng chọn ảnh chính.';
    }

    if ($descImage['error'] === UPLOAD_ERR_OK) {
        $descName = time() . '_desc_' . basename($descImage['name']);
        if (!move_uploaded_file($descImage['tmp_name'], $detailDir . $descName)) {
            $errors[] = 'Lỗi khi lưu ảnh mô tả.';
        }
    } else {
        $descName = null;  // Ảnh mô tả không bắt buộc
    }

    if (empty($errors)) {
        $sql = "INSERT INTO menu_items
            (name, price, description, image_path, description_image, created_at, type, category)
            VALUES
            (:name, :price, :description, :image_path, :description_image, NOW(), :type, :category)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'              => $name,
            ':price'             => $price,
            ':description'       => $description,
            ':image_path'        => $mainName,
            ':description_image' => $descName,
            ':type'              => $type,
            ':category'          => $category
        ]);

        header("Location: quan_ly_menu.php");
        exit();
    }
}
?>
