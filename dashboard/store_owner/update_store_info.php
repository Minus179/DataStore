<?php
session_start();
require_once __DIR__ . '/../../includes/db.php'; // $pdo kết nối PDO

// Check đăng nhập & phân quyền chủ quán
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'store_owner') {
    header('Location: ../../login/login.php');
    exit();
}

$owner_id = $_SESSION['user_id'];

// Lấy dữ liệu từ form
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$description = trim($_POST['description'] ?? '');

// Validate đơn giản
if (empty($name) || empty($address) || empty($phone) || empty($email)) {
    $_SESSION['error'] = "Vui lòng điền đầy đủ các trường bắt buộc!";
    header("Location: ../store_owner/quan_ly_menu.php#info");
    exit();
}

// Validate email chuẩn
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email không hợp lệ!";
    header("Location: ../store_owner/quan_ly_menu.php#info");
    exit();
}

// Xử lý upload ảnh (nếu có)
$avatarPath = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../../assets/images/store/'; // đường dẫn vật lý server

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = $_FILES['avatar']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Chỉ cho phép định dạng ảnh
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExt, $allowedExts)) {
        $_SESSION['error'] = "Chỉ được upload file ảnh với định dạng: jpg, jpeg, png, gif";
        header("Location: ../store_owner/quan_ly_menu.php#info");
        exit();
    }

    // Đặt tên file mới tránh trùng
    $newFileName = 'store_' . $owner_id . '_' . time() . '.' . $fileExt;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Đường dẫn lưu vào DB: tương đối với root web
        $avatarPath = 'assets/images/store/' . $newFileName;
    } else {
        $_SESSION['error'] = "Tải ảnh lên thất bại!";
        header("Location: ../store_owner/quan_ly_menu.php#info");
        exit();
    }
}

try {
    // Kiểm tra bản ghi quán đã có chưa
    $stmtCheck = $pdo->prepare("SELECT id FROM store_info WHERE owner_id = ?");
    $stmtCheck->execute([$owner_id]);
    $store = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($store) {
        // Update quán
        if ($avatarPath) {
            $stmtUpdate = $pdo->prepare("UPDATE store_info SET name = ?, address = ?, phone = ?, email = ?, description = ?, avatar = ? WHERE owner_id = ?");
            $stmtUpdate->execute([$name, $address, $phone, $email, $description, $avatarPath, $owner_id]);
        } else {
            $stmtUpdate = $pdo->prepare("UPDATE store_info SET name = ?, address = ?, phone = ?, email = ?, description = ? WHERE owner_id = ?");
            $stmtUpdate->execute([$name, $address, $phone, $email, $description, $owner_id]);
        }
    } else {
        // Thêm mới quán
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
