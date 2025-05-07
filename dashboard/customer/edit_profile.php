<?php
session_start();

// Kiểm tra đăng nhập và phân quyền
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

include("../../includes/db.php");

// Lấy thông tin người dùng
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, phone, email, address, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Không tìm thấy thông tin người dùng.";
    exit();
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Kiểm tra mật khẩu
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!password_verify($password, $row['password'])) {
        echo "<script>alert('Mật khẩu không chính xác. Vui lòng thử lại.'); window.location.href = 'edit_profile.php';</script>";
        exit();
    }

    // Cập nhật thông tin người dùng
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $phone, $email, $address, $user_id);
    $stmt->execute();

    // Xử lý ảnh đại diện nếu có
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar_tmp_name = $_FILES['avatar']['tmp_name'];
        $avatar_name = basename($_FILES['avatar']['name']);
        $avatar_extension = strtolower(pathinfo($avatar_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($avatar_extension, $allowed_extensions)) {
            $new_avatar_name = uniqid('avatar_', true) . '.' . $avatar_extension;
            $upload_dir = "../../assets/images/picture/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $upload_path = $upload_dir . $new_avatar_name;
            if (move_uploaded_file($avatar_tmp_name, $upload_path)) {
                $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->bind_param("si", $new_avatar_name, $user_id);
                $stmt->execute();
            }
        }
    }

    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/customer/edit_profile.css?=<?php echo time(); ?>">
</head>
<body>

<header class="header">
    <h1>Chỉnh sửa thông tin cá nhân</h1>
</header>

<main>
    <a href="profile.php" class="back-btn" title="Quay lại trang trước">
        <i class="fas fa-arrow-left"></i>
    </a>

    <section class="container">
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="avatar">Chọn ảnh đại diện mới:</label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="../../assets/images/picture/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar hiện tại" style="max-width: 100px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">Xác nhận mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-buttons">
                <button type="submit" class="update-btn">Cập nhật</button>
            </div>
        </form>
    </section>
</main>

</body>
</html>
