<?php
// Kết nối cơ sở dữ liệu
include("../includes/db.php");

// Khai báo biến
$error = "";
$success = "";

// Hàm tạo mã xác nhận ngẫu nhiên
function generate_verification_code() {
    return rand(100000, 999999); // Mã xác nhận 6 số
}

// Xác định vai trò cho trang này
$role = "store_owner";

// Xử lý POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $re_password = mysqli_real_escape_string($conn, $_POST['re_password']);
    $verification_code = generate_verification_code();

    // Kiểm tra mật khẩu xác nhận
    if ($password != $re_password) {
        $error = "❌ Mật khẩu và mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra số điện thoại đã đăng ký chưa
        $check_phone = mysqli_query($conn, "SELECT registration_count FROM users WHERE phone = '$phone'");

        if (mysqli_num_rows($check_phone) > 0) {
            $row = mysqli_fetch_assoc($check_phone);
            $registration_count = $row['registration_count'];

            if ($registration_count >= 3) {
                $error = "❌ Số điện thoại này đã đăng ký tối đa 3 lần!";
            } else {
                // Kiểm tra email đã tồn tại chưa
                $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
                if (mysqli_num_rows($check_email) > 0) {
                    $error = "❌ Email đã tồn tại!";
                } else {
                    // Mã hóa mật khẩu
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Thêm user vào cơ sở dữ liệu
                    $insert = "INSERT INTO users (email, password, role, name, phone, verification_code, registration_count) 
                               VALUES ('$email', '$hashed_password', '$role', '$name', '$phone', '$verification_code', 1)";

                    if (mysqli_query($conn, $insert)) {
                        // Lấy user_id vừa tạo
                        $user_id = mysqli_insert_id($conn);

                        // Cập nhật số lần đăng ký
                        mysqli_query($conn, "UPDATE users SET registration_count = 1 WHERE phone = '$phone'");

                        // Chuyển hướng đến trang verify_code.php với user_id và verification_code
                        header("Location: verify_code.php?user_id=$user_id&verification_code=$verification_code");
                        exit(); // Dừng script sau khi chuyển hướng
                    } else {
                        $error = "❌ Lỗi hệ thống: " . mysqli_error($conn);
                    }
                }
            }
        } else {
            // Nếu số điện thoại chưa đăng ký, tiến hành đăng ký
            $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
            if (mysqli_num_rows($check_email) > 0) {
                $error = "❌ Email đã tồn tại!";
            } else {
                // Mã hóa mật khẩu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Thêm user vào cơ sở dữ liệu
                $insert = "INSERT INTO users (email, password, role, name, phone, verification_code, registration_count) 
                           VALUES ('$email', '$hashed_password', '$role', '$name', '$phone', '$verification_code', 1)";

                if (mysqli_query($conn, $insert)) {
                    // Lấy user_id vừa tạo
                    $user_id = mysqli_insert_id($conn);

                    // Cập nhật số lần đăng ký
                    mysqli_query($conn, "UPDATE users SET registration_count = 1 WHERE phone = '$phone'");

                    // Chuyển hướng đến trang verify_code.php với user_id và verification_code
                    header("Location: verify_code.php?user_id=$user_id&verification_code=$verification_code");
                    exit(); // Dừng script sau khi chuyển hướng
                } else {
                    $error = "❌ Lỗi hệ thống: " . mysqli_error($conn);
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
    <title>Đăng ký Chủ Cửa Hàng</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <header>
        <h1>🏪 DATASTORE FOOD - Đăng ký Chủ Cửa Hàng</h1>
    </header>

    <main>
        <section class="container">
            <h2>Đăng ký tài khoản Chủ Cửa Hàng</h2>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Số điện thoại" required>
                <input type="text" name="name" placeholder="Họ và tên" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <input type="password" name="re_password" placeholder="Nhập lại mật khẩu" required>
                <button type="submit">Đăng ký</button>
            </form>

            <div class="login-link">
                Đã có tài khoản? <a href="../login/login.php">Đăng nhập ngay</a>
            </div>
        </section>
    </main>

    <footer>
        <p>IT_STARTUP TEAM - Khởi nghiệp cùng bạn!</p>
    </footer>
</body>
</html>
