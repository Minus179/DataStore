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
$user = mysqli_fetch_assoc($result);
$current_points = $user['points'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý vòng quay: Cộng điểm cho người chơi
    $earned_points = rand(1, 100);  // Số điểm ngẫu nhiên người chơi nhận được (từ 1 đến 100)

    // Cập nhật điểm người dùng trong cơ sở dữ liệu
    $new_points = $current_points + $earned_points;
    $update_query = "UPDATE users SET points = '$new_points' WHERE id = '$user_id'";
    mysqli_query($conn, $update_query);

    // Lưu lịch sử game vào bảng mini_game_history
    $game_name = "Vòng quay may mắn";
    $insert_history = "INSERT INTO mini_game_history (user_id, game_name, points_earned) 
                       VALUES ('$user_id', '$game_name', '$earned_points')";
    mysqli_query($conn, $insert_history);

    // Hiển thị kết quả
    $message = "Chúc mừng! Bạn nhận được $earned_points điểm. Tổng điểm hiện tại: $new_points.";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mini Game - Vòng quay may mắn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
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
            deg = Math.floor(Math.random() * 360) + 1800;
            document.getElementById('segment').style.transition = "transform 3s ease-out";
            document.getElementById('segment').style.transform = `rotate(${deg}deg)`;

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
                    document.getElementById("result").innerText = data;
                });
            }, 3000);
        }
    </script>
</body>
</html>
