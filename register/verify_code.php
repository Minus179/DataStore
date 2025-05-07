<?php
// Kết nối cơ sở dữ liệu
include("../includes/db.php");

// Lấy thông tin từ URL
$user_id = $_GET['user_id'];
$verification_code = $_GET['verification_code'];

// Khai báo thông báo lỗi và thành công
$error = "";
$success = "";
$wait_time = 0; // Thời gian chờ 10s nếu mã xác nhận sai

// Xử lý khi người dùng nhập mã xác nhận
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = mysqli_real_escape_string($conn, $_POST['verification_code']);

    if ($entered_code == $verification_code) {
        // Kiểm tra xem tài khoản đã được xác nhận chưa
        $check_verified = mysqli_query($conn, "SELECT verified FROM users WHERE id = '$user_id'");
        $user = mysqli_fetch_assoc($check_verified);

        if ($user['verified'] == 1) {
            $error = "❌ Tài khoản này đã được xác nhận trước đó!";
        } else {
            // Cập nhật trạng thái xác nhận cho người dùng
            $update = "UPDATE users SET verified = 1 WHERE id = '$user_id'";
            if (mysqli_query($conn, $update)) {
                $success = "✅ Xác nhận thành công! Bạn đã trở thành chủ cửa hàng.";

                // Chuyển hướng tới trang đăng nhập sau 2 giây
                header("refresh:2;url=../login/login.php");
                exit(); // Dừng script sau khi chuyển hướng
            } else {
                $error = "❌ Lỗi hệ thống: " . mysqli_error($conn);
            }
        }
    } else {
        // Mã xác nhận sai, yêu cầu người dùng đợi 10 giây
        $error = "❌ Mã xác nhận không đúng! Vui lòng kiểm tra lại. Bạn phải chờ 10 giây để nhập lại mã.";
        $wait_time = 10; // Đặt thời gian chờ 10 giây
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận tài khoản - DATASTORE FOOD</title>
    <link rel="stylesheet" href="../assets/css/register.css">
    <script>
        let waitTime = <?php echo $wait_time; ?>;
        let newVerificationCode = '<?php echo $verification_code; ?>'; // Giữ mã xác nhận cũ
        let countdownElement;

        function generateNewVerificationCode() {
            // Tạo mã xác nhận mới ngẫu nhiên
            newVerificationCode = Math.floor(Math.random() * 1000000);
            document.getElementById('verification_code_display').textContent = "Mã xác nhận mới: " + newVerificationCode;
        }

        // Thực hiện đếm ngược khi thời gian chờ còn
        if (waitTime > 0) {
            countdownElement = document.getElementById('countdown');
            let countdownInterval = setInterval(function() {
                countdownElement.textContent = `Vui lòng đợi ${waitTime} giây để nhận mã xác nhận mới.`;
                if (waitTime <= 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('verification_code').disabled = false;
                    document.getElementById('submit_button').disabled = false;
                    generateNewVerificationCode();
                }
                waitTime--;
            }, 1000);
        }
    </script>
</head>
<body>
    <header>
        <h1>🏪 DATASTORE FOOD - Xác nhận tài khoản</h1>
    </header>

    <main>
        <section class="container">
            <h2>Xác nhận mã để hoàn tất đăng ký</h2>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
                <?php if ($wait_time > 0): ?>
                    <div id="countdown"></div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="verification_code">Mã xác nhận của bạn: <span id="verification_code_display"><?php echo $verification_code; ?></span></label>
                <input type="text" name="verification_code" id="verification_code" placeholder="Nhập mã xác nhận" required <?php echo ($wait_time > 0) ? 'disabled' : ''; ?>>
                <button type="submit" id="submit_button" <?php echo ($wait_time > 0) ? 'disabled' : ''; ?>>Xác nhận</button>
            </form>

            <div class="login-link">
                <a href="../login/login.php">Đăng nhập ngay</a>
            </div>
        </section>
    </main>

    <footer>
        <p>IT_STARTUP TEAM - Khởi nghiệp cùng bạn!</p>
    </footer>
</body>
</html>
