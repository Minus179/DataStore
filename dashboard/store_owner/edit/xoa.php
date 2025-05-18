<?php
session_start();
require_once __DIR__ . '/../../../includes/db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    http_response_code(403);
    echo 'Không có quyền truy cập';
    exit();
}

// Lấy ID từ POST hoặc GET
$id = $_POST['id'] ?? $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo 'Thiếu ID món';
    exit();
}

// Lấy thông tin món từ cơ sở dữ liệu
$stmt = $pdo->prepare("SELECT id, image_path, type FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    http_response_code(404);
    echo 'Không tìm thấy món';
    exit();
}

// Xóa ảnh liên quan nếu tồn tại
$imagePaths = [
    __DIR__ . '/../../../assets/images/' . $item['type'] . '/' . $item['image_path'],
    __DIR__ . '/../../../assets/images/' . $item['type'] . '/desc1_' . $item['image_path'],
    __DIR__ . '/../../../assets/images/' . $item['type'] . '/desc2_' . $item['image_path']
];
foreach ($imagePaths as $imagePath) {
    if (file_exists($imagePath) && is_file($imagePath)) {
        unlink($imagePath);
    }
}

// Xóa các bản ghi liên quan trong item_images (xóa dữ liệu con trước)
$stmtDeleteImages = $pdo->prepare("DELETE FROM item_images WHERE item_id = ?");
$stmtDeleteImages->execute([$id]);

// Xóa món trong menu_items
$stmtDelete = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
$stmtDelete->execute([$id]);


if ($stmtDelete->rowCount() > 0) {
    echo 'success';
} else {
    echo 'Không thể xóa món, vui lòng thử lại sau';
}
?>