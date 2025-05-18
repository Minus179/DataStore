<?php
session_start();
require_once __DIR__ . '/../../../includes/db.php';

// ✅ Kiểm tra đăng nhập và phân quyền chủ quán
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'store_owner') {
    header('Location: ../../login/login.php');
    exit();
}

$owner_id = $_SESSION['user_id'];

// ✅ Nhận dữ liệu từ form (dùng trim để loại khoảng trắng đầu/cuối)
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$description = trim($_POST['description'] ?? '');

// ✅ Kiểm tra dữ liệu đầu vào
if (empty($name) || empty($address) || empty($phone) || empty($email)) {
    $_SESSION['error'] = "Vui lòng điền đầy đủ các trường bắt buộc!";
    header("Location: ../quan_ly_menu.php#info"); // Quay về tab Thông tin quán
    exit();
}

// ✅ Kiểm tra định dạng email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email không hợp lệ!";
    header("Location: ../quan_ly_menu.php#info");
    exit();
}

// ✅ Kiểm tra xem đã có thông tin quán chưa
try {
    $stmt = $pdo->prepare("SELECT id FROM store_info WHERE owner_id = ?");
    $stmt->execute([$owner_id]);
    $store = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($store) {
        // ✅ Cập nhật thông tin
        $stmt = $pdo->prepare("UPDATE store_info SET name = ?, address = ?, phone = ?, email = ?, description = ? WHERE owner_id = ?");
        $stmt->execute([$name, $address, $phone, $email, $description, $owner_id]);
    } else {
        // ✅ Chèn mới nếu chưa có
        $stmt = $pdo->prepare("INSERT INTO store_info (owner_id, name, address, phone, email, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$owner_id, $name, $address, $phone, $email, $description]);
    }

    // ✅ Thông báo thành công
    $_SESSION['success'] = "Cập nhật thông tin quán thành công!";
    header("Location: ../quan_ly_menu.php#info");
    exit();

} catch (PDOException $e) {
    // ⚠️ Xử lý lỗi truy vấn
    $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
    header("Location: ../quan_ly_menu.php#info");
    exit();
}
