<?php
session_start();
include("../includes/db.php");

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $code = rand(100000, 999999); // Mã xác thực 6 chữ số
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $code;

        // Gửi email
        $subject = "Mã xác thực đặt lại mật khẩu - DATASTORE FOOD";
        $message = "Mã xác thực của bạn là: $code";
        $headers = "From: datastore@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: verify_code.php");
            exit();
        } else {
            $error = "❌ Gửi email thất bại. Vui lòng thử lại.";
        }
    } else {
        $error = "❌ Email không tồn tại trong hệ thống!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="../assets/css/login.css?v=<?=time()?>">
</head>
<body>
    <main class="container login-box">
        <h2>🔐 Quên mật khẩu</h2>

        <?php if ($error): ?><div class="message error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="message success"><?= $success ?></div><?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Nhập email đã đăng ký" required>
            <button type="submit">Gửi mã xác thực</button>
        </form>
    </main>
</body>
</html>
