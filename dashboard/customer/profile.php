<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

include("../../includes/db.php");

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/customer/profile.css">
</head>
<body>

<header class="header">
    <h1>Thông tin cá nhân</h1>
</header>

<main>
    <section class="container">
        <h2>Xin chào, <?php echo $user['name']; ?>!</h2>

        <div class="info-block">
            <p><strong>📛 Họ tên:</strong><span><?php echo $user['name']; ?></span></p>
            <p><strong>📞 Số điện thoại:</strong><span><?php echo $user['phone']; ?></span></p>
            <p><strong>📧 Email:</strong><span><?php echo $user['email']; ?></span></p>
            <p><strong>🏠 Địa chỉ:</strong><span><?php echo $user['address']; ?></span></p>
        </div>

        <div class="form-buttons">
            <a href="edit_profile.php" class="update-btn">
                <i class="fas fa-pen"></i> Chỉnh sửa
            </a>
        </div>
    </section>
</main>

</body>
</html>
