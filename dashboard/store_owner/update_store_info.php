<?php
session_start();
require_once __DIR__ . '/../../includes/db.php'; // $pdo kết nối PDO

// ✅ Kiểm tra đăng nhập và quyền chủ quán
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'store_owner') {
    header('Location: ../../login/login.php');
    exit();
}

$owner_id = $_SESSION['user_id'];

// ✅ Lấy dữ liệu từ form
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$description = trim($_POST['description'] ?? '');

// ✅ Kiểm tra các trường bắt buộc
if (!$name || !$address || !$phone || !$email) {
    $_SESSION['error'] = "Vui lòng điền đầy đủ các trường bắt buộc!";
    header("Location: ../store_owner/quan_ly_menu.php#info");
    exit();
}

// ✅ Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email không hợp lệ!";
    header("Location: ../store_owner/quan_ly_menu.php#info");
    exit();
}

// ✅ Xử lý ảnh nếu có
$avatarPath = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../../assets/images/store/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = $_FILES['avatar']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExt, $allowedExts)) {
        $_SESSION['error'] = "Chỉ được upload ảnh: jpg, jpeg, png, gif!";
        header("Location: ../store_owner/quan_ly_menu.php#info");
        exit();
    }

    $newFileName = 'store_' . $owner_id . '_' . time() . '.' . $fileExt;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $avatarPath = 'assets/images/store/' . $newFileName; // đường dẫn lưu DB
    } else {
        $_SESSION['error'] = "Tải ảnh thất bại!";
        header("Location: ../store_owner/quan_ly_menu.php#info");
        exit();
    }
}

try {
    // ✅ Kiểm tra quán đã tồn tại chưa
    $stmtCheck = $pdo->prepare("SELECT id FROM store_info WHERE owner_id = ?");
    $stmtCheck->execute([$owner_id]);
    $store = $stmtCheck->fetch();

    if ($store) {
        // 🔁 Cập nhật
        $sql = "UPDATE store_info SET name = ?, address = ?, phone = ?, email = ?, description = ?";
        $params = [$name, $address, $phone, $email, $description];

        if ($avatarPath) {
            $sql .= ", avatar = ?";
            $params[] = $avatarPath;
        }

        $sql .= " WHERE owner_id = ?";
        $params[] = $owner_id;

        $stmtUpdate = $pdo->prepare($sql);
        $stmtUpdate->execute($params);
    } else {
        // ➕ Thêm mới
        $stmtInsert = $pdo->prepare("INSERT INTO store_info (owner_id, name, address, phone, email, description, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->execute([$owner_id, $name, $address, $phone, $email, $description, $avatarPath]);
    }

    $_SESSION['success'] = "Cập nhật thông tin quán thành công!";
    header("Location: ../store_owner/quan_ly_menu.php#info");
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
    header("Location: ../store_owner/quan_ly_menu.php#info");
    exit();
}
