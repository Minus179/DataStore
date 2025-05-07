<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

$user_id = $_SESSION['user_id'];

// Lấy điểm hiện tại của người dùng từ cơ sở dữ liệu
$query = "SELECT points FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
if ($result) {
    $user = mysqli_fetch_assoc($result);
    $current_points = $user['points'];
} else {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý vòng quay: Cộng điểm cho người chơi
    $earned_points = rand(1, 100);  // Số điểm ngẫu nhiên người chơi nhận được (từ 1 đến 100)

    // Cập nhật điểm người dùng trong cơ sở dữ liệu
    $new_points = $current_points + $earned_points;
    $update_query = "UPDATE users SET points = '$new_points' WHERE id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        $message = "Chúc mừng! Bạn nhận được $earned_points điểm. Tổng điểm hiện tại: $new_points.";
    } else {
        $message = "Có lỗi xảy ra khi cập nhật điểm.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mini Game - Vòng quay may mắn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        /* Thêm kiểu dáng cho vòng quay */
        .wheel {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 10px solid #4CAF50;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            background-color: #f1f1f1;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .wheel div {
            width: 50%;
            height: 50%;
            background-color: #FFC107;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 50%;
            transform-origin: 100% 100%;
        }

        .back-btn {
            margin: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <button class="back-btn" onclick="history.back()">⬅️ Quay lại</button>

    <main>
        <h2>Mini Game - Vòng quay may mắn</h2>
        <p>Quay vòng quay để nhận quà và điểm thưởng!</p>

        <div class="wheel" id="wheel">
            <div id="segment" style="transform: rotate(0deg);"></div>
        </div>

        <button onclick="spinWheel()" class="back-btn">Quay vòng quay</button>

        <p id="result" style="font-size: 18px;"></p>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </main>

    <script>
        let deg = 0;

        function spinWheel() {
            // Randomly generate a rotation degree
            deg = Math.floor(Math.random() * 360) + 1800; // Cho vòng quay quay từ 1800 độ đến 2160 độ
            document.getElementById('segment').style.transition = "transform 3s ease-out";
            document.getElementById('segment').style.transform = `rotate(${deg}deg)`;

            // Gửi yêu cầu POST để cập nhật điểm
            setTimeout(() => {
                fetch("<?php echo $_SERVER['PHP_SELF']; ?>", {
                    method: "POST",
                    body: new URLSearchParams({
                        'spin': 'true',
                    }),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("result").innerText = data; // Hiển thị kết quả
                });
            }, 3000); // Chờ vòng quay hoàn tất (3 giây)
        }
    </script>
</body>
</html>
