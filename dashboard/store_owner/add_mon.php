<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    header("Location:/../../login/login.php");
    exit();
}

 require_once __DIR__ . '/../../includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_FILES['image'] ?? null;

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $uploadDir = __DIR__ . '../../../assets/images/' . $type . '/';

        if (!move_uploaded_file($image['tmp_name'], $uploadDir . $filename)) {
            $errors[] = 'Lỗi khi tải ảnh lên.';
        }
    } else {
        $errors[] = 'Vui lòng chọn ảnh.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, price, type, description, image_path, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $price, $type, $description, $filename]);
        header('Location: quan_ly_menu.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm món mới</title>
    <link rel="stylesheet" href="../../assets/css/store_owner/home.css?v=<?=time()?>" />
    <link rel="stylesheet" href="../../assets/css/store_owner/home_1.css?v=<?=time()?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
<?php include __DIR__ . '../sidebar.php'; ?>
<main class="main-content">
     <header class="top-header">
        <button class="tab-button" onclick="location.href='quan_ly_menu.php'">Tất cả món</button>
        <button class="tab-button" onclick="location.href='add_mon.php'">Thêm món</button>
        <button class="tab-button" onclick="location.href='edit_mon.php'">Sửa / Xóa món</button>
        <button class="tab-button" data-target="profile_store">Cập nhật thông tin</button>
    </header>
    <h2>Thêm món mới</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Tên món:
            <input type="text" name="name" required>
        </label>
        <label>Giá:
            <input type="number" name="price" required>
        </label>
        <label>Loại:
            <select name="type" required>
                <option value="food">Món ăn</option>
                <option value="drink">Đồ uống</option>
            </select>
        </label>
        <label>Mô tả:
            <textarea name="description" rows="3"></textarea>
        </label>
        <label>Ảnh minh hoạ:
            <input type="file" name="image" accept="image/*" required>
        </label>
        <button type="submit">Thêm món</button>
    </form>
</main>
</body>
</html>