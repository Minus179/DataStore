<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Bao gồm tệp db.php
include($_SERVER['DOCUMENT_ROOT'] . "/DataStore/includes/db.php");

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra mật khẩu hiện tại
    $password = $_POST['password'];
    if (empty($password)) {
        $error = "❌ Mật khẩu không được để trống!";
    } else {
        // Kiểm tra mật khẩu nhập vào với mật khẩu lưu trong CSDL
        if (!password_verify($password, $user['password'])) {
            $error = "❌ Mật khẩu không chính xác!";
        } else {
            // Nếu mật khẩu đúng, tiếp tục cập nhật thông tin
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);

            if (empty($name) || empty($phone) || empty($email) || empty($address)) {
                $error = "❌ Vui lòng điền đầy đủ thông tin!";
            } else {
                $update_query = "UPDATE users SET name = '$name', phone = '$phone', email = '$email', address = '$address' WHERE id = '$user_id'";
                if (mysqli_query($conn, $update_query)) {
                    $success = "✅ Cập nhật thông tin thành công!";
                    $_SESSION['user_name'] = $name;  // Cập nhật tên trong session
                    header("Location: profile.php");
                    exit();
                } else {
                    $error = "❌ Đã xảy ra lỗi khi cập nhật!";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin</title>
    <link rel="stylesheet" href="../../assets/css/customer/edit_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="header">
    <h1>Thông tin cá nhân</h1>
</header>

<main>
    <section class="container">
        <h2>Chỉnh sửa thông tin cá nhân</h2>

        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="edit_profile.php" method="POST" class="edit-form">
            <label for="name">Họ và tên</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

            <label for="phone">Số điện thoại</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="address">Địa chỉ hiện tại</label>
            <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>

            <label for="password">Mật khẩu hiện tại</label>
            <input type="password" id="password" name="password" required>

            <div class="form-buttons">
                <button type="submit" class="update-btn">Cập nhật</button>
                <a href="../../dashboard/customer/home.php" class="back-btn" title="Quay lại">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </form>
    </section>
</main>

</body>
</html>
