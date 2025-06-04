<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    header("Location: ../../login/login.php");
    exit();
}

 require_once __DIR__ . '/../../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: edit_mon.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header('Location: edit_mon.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';

    $filename = $item['image_path'];
    $image = $_FILES['image'] ?? null;

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $uploadDir = __DIR__ . '../../../assets/images/' . $type . '/';

        move_uploaded_file($image['tmp_name'], $uploadDir . $filename);
    }

    $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, price = ?, type = ?, description = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$name, $price, $type, $description, $filename, $id]);

    header("Location: edit_mon.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa món</title>
    <link rel="stylesheet" href="../../assets/css/store_owner/home.css">
    <link rel="stylesheet" href="../../assets/css/store_owner/home_1.css">
</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>
<main class="main-content">
    <h2>Sửa thông tin món</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Tên món:
            <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
        </label>
        <label>Giá:
            <input type="number" name="price" value="<?= $item['price'] ?>" required>
        </label>
        <label>Loại:
            <select name="type" required>
                <option value="food" <?= $item['type'] === 'food' ? 'selected' : '' ?>>Món ăn</option>
                <option value="drink" <?= $item['type'] === 'drink' ? 'selected' : '' ?>>Đồ uống</option>
            </select>
        </label>
        <label>Mô tả:
            <textarea name="description" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
        </label>
        <label>Ảnh minh hoạ hiện tại:
            <img src="../assets/images/<?= $item['type'] ?>/<?= $item['image_path'] ?>" style="max-width:200px; display:block; margin:10px 0;">
        </label>
        <label>Đổi ảnh (tuỳ chọn):
            <input type="file" name="image" accept="image/*">
        </label>
        <button type="submit">Cập nhật</button>
    </form>
</main>
</body>
</html>
