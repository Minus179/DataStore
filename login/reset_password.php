<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($pass != $confirm) {
        $error = "❌ Mật khẩu không khớp!";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];
        $query = "UPDATE users SET password='$hash' WHERE email='$email'";
        if (mysqli_query($conn, $query)) {
            session_destroy(); // Clear session
            $success = "✅ Đổi mật khẩu thành công! <a href='login.php'>Đăng nhập ngay</a>";
        } else {
            $error = "❌ Có lỗi xảy ra. Thử lại sau.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="../assets/css/login.css?v=<?=time()?>">
</head>
<body>
    <main class="container login-box">
        <h2>🔐 Đặt lại mật khẩu</h2>

        <?php if ($error): ?><div class="message error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="message success"><?= $success ?></div><?php endif; ?>

        <form method="POST">
            <input type="password" name="password" placeholder="Mật khẩu mới" required>
            <input type="password" name="confirm" placeholder="Xác nhận lại mật khẩu" required>
            <button type="submit">Cập nhật mật khẩu</button>
        </form>
    </main>
</body>
</html>

