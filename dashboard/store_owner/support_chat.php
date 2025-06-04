<?php
session_start();
require '../../includes/PHPMailer/src/PHPMailer.php';
require '../../includes/PHPMailer/src/SMTP.php';
require '../../includes/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "datastore_food");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'store_owner') {
    header("Location: ../../../login/login.php");
    exit();
}

$selected_user = $_GET['user_id'] ?? null;

// ===== GỬI TIN NHẮN TỪ STORE_OWNER =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && isset($_POST['user_id'])) {
    $message = trim($_POST['message']);
    $user_id = (int) $_POST['user_id'];
    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO support_chat (user_id, sender, message) VALUES (?, 'store_owner', ?)");
        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();

        exit();
    }
}

// ===== TẢI TIN NHẮN CHAT VỚI 1 USER =====
if (isset($_GET['load']) && isset($_GET['user_id'])) {
    $uid = (int) $_GET['user_id'];
    $result = $conn->query("SELECT * FROM support_chat WHERE user_id = $uid ORDER BY sent_at ASC");
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . ($row['sender'] === 'store_owner' ? "Bạn" : "Khách hàng") . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
    }
    exit();
}

// ===== DANH SÁCH KHÁCH HÀNG ĐÃ CHAT =====
$customers = $conn->query("SELECT DISTINCT user_id FROM support_chat ORDER BY sent_at DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Hỗ trợ khách hàng</title>
    <link rel="stylesheet" href="../../assets/css/customer/chat.css?v=<?=time()?>" />
    <link rel="stylesheet" href="../../assets/css/store_owner/home.css?v=<?=time()?>" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; }
        a { text-decoration: none; color: #2980b9; }
        a:hover { text-decoration: underline; }
        .chat-item { margin-bottom: 5px; }
    </style>
</head>
<body>

<!-- ✅ Dashboard Layout -->
<div class="dashboard-container">
    <?php include 'sidebar.php'; ?>

        <!-- Header ngang trên đầu -->
    <header class="top-header">
        <button class="tab-button active" data-target="all">Tất cả món</button>
        <button class="tab-button" data-target="food">Món ăn</button>
        <button class="tab-button" data-target="drink">Món nước</button>
        <button class="tab-button" data-target="info">Thông tin quán</button>
        <button class="tab-button" id="logout-button">Đăng xuất</button>
    </header>

    <!-- ✅ Nội dung chính -->
    <main class="main-content" style="padding: 20px;">
        <h2>💬 Hỗ trợ khách hàng</h2>

        <?php if (!$selected_user): ?>
            <h3>Danh sách khách đã nhắn:</h3>
            <ul>
                <?php while ($row = $customers->fetch_assoc()): ?>
                    <li>
                        <a href="?user_id=<?= $row['user_id'] ?>">👤 Khách ID <?= $row['user_id'] ?></a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <a href="support_chat.php" style="color:#e67e22;">⬅ Quay lại danh sách</a>

            <div id="chat-box" style="height:300px; overflow-y:auto; border:1px solid #ccc; padding:10px; margin-top:10px; background:#fdfdfd;"></div>

            <form id="chat-form" method="POST" style="margin-top:10px; display:flex; gap:10px;">
                <input type="hidden" name="user_id" value="<?= $selected_user ?>">
                <input type="text" id="message" name="message" placeholder="Nhập tin phản hồi..." required style="flex:1; padding:8px;">
                <button type="submit" style="padding:8px 12px; background:#27ae60; color:white; border:none; border-radius:4px;">Gửi</button>
            </form>

            <script>
                function loadMessages() {
                    $.get("support_chat.php?load=1&user_id=<?= $selected_user ?>", function (data) {
                        $("#chat-box").html(data);
                    });
                }

                $("#chat-form").submit(function (e) {
                    e.preventDefault();
                    $.post("support_chat.php", {
                        message: $("#message").val(),
                        user_id: <?= $selected_user ?>
                    }, function () {
                        $("#message").val('');
                        loadMessages();
                    });
                });

                setInterval(loadMessages, 3000);
                loadMessages();
            </script>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
