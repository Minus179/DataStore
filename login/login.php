<!-- login.php -->
<?php
session_start();
include("../includes/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE phone = '$phone'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            switch ($user['role']) {
                case 'customer':
                    header("Location: ../dashboard/customer/home.php");
                    break;
                case 'store_owner':
                    header("Location: ../dashboard/store_owner/home.php");
                    break;
                case 'shipper':
                    header("Location: ../dashboard/shipper/home.php");
                    break;
                default:
                    $error = "❌ Vai trò không xác định!";
                    break;
            }
            exit();
        } else {
            $error = "❌ Mật khẩu không đúng!";
        }
    } else {
        $error = "❌ Không tìm thấy tài khoản với số điện thoại này!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - DATASTORE FOOD</title>
    <link rel="stylesheet" href="../assets/css/login.css?v=<?=time()?>"> <!--Mỗi lần reload, ?v=123456 khác nhau nên trình duyệt buộc phải tải file mới.-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>🍔 DATASTORE FOOD - Đăng nhập</h1>
    </header>

    <main>
        <section class="container login-box">
            <h2>Đăng nhập</h2>

            <?php if (!empty($error)): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="text" name="phone" placeholder="Số điện thoại" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit">Đăng nhập</button>
            </form>

            <div class="forgot-password">
                <a href="forgot_password.php">Quên mật khẩu?</a>
            </div>

            <div class="register-link">
                Chưa có tài khoản? <a href="../register/register_choice.php">Đăng ký ngay</a>
            </div>
        </section>
    </main>

    <footer>
        <p>IT_STARTUP TEAM - Khởi nghiệp cùng bạn!</p>
    </footer>
</body>
</html>
