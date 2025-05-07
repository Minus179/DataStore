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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/customer/profile.css?v=<?=time()?>">
</head>
<body>

<header class="header">
    <h1>Thông tin cá nhân</h1>
</header>

<main>
    <a href="../../dashboard/customer/home.php" class="back-btn" title="Quay lại trang trước">
        <i class="fas fa-arrow-left"></i>
    </a>

    <section class="container">
        <div class="flex-container">
            <!-- Cột trái - Tiêu đề + ảnh đại diện -->
            <div class="title-section">
                <h2>Xin chào, <?php echo htmlspecialchars($user['name']); ?>!</h2>
                <div class="profile-section">
                    <img src="<?php echo !empty($user['avatar']) ? '../../assets/images/picture/'.htmlspecialchars($user['avatar']) : '../../assets/images/default-avatar.png'; ?>" class="profile-img" alt="Ảnh đại diện">
                </div>
            </div>

            <!-- Cột phải - Thông tin khách hàng -->
            <div class="info-section">
                <div class="info-block">
                    <p><strong>📛 Họ tên:</strong> <span><?php echo htmlspecialchars($user['name']); ?></span></p>
                    <p><strong>📞 Số điện thoại:</strong> <span><?php echo htmlspecialchars($user['phone']); ?></span></p>
                    <p><strong>📧 Email:</strong> <span><?php echo htmlspecialchars($user['email']); ?></span></p>
                    <p><strong>🏠 Địa chỉ:</strong> <span><?php echo htmlspecialchars($user['address']); ?></span></p>
                </div>

                <div class="form-buttons">
                    <a href="edit_profile.php" class="update-btn" title="Chỉnh sửa thông tin cá nhân">
                        <i class="fas fa-pen"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

</body>
</html>
