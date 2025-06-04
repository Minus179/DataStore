<?php
session_start();

require '../../includes/PHPMailer/src/PHPMailer.php';
require '../../includes/PHPMailer/src/SMTP.php';
require '../../includes/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "datastore_food");
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// ======== GỬI TIN NHẮN TỰ ĐỘNG KHI TRUY CẬP TỪ ĐƠN HÀNG =========
if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];

    // Kiểm tra đã gửi hỗ trợ cho đơn này chưa
    $check = $conn->prepare("SELECT id FROM support_chat WHERE user_id = ? AND message LIKE ? LIMIT 1");
    $likeMsg = "%Đơn hàng #$order_id%";
    $check->bind_param("is", $user_id, $likeMsg);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        // Lấy thông tin đơn hàng
        $stmt = $conn->prepare("SELECT o.id, u.fullname, o.total_amount, o.payment_method, o.created_at 
                                FROM orders o 
                                JOIN users u ON o.user_id = u.id 
                                WHERE o.id = ? AND o.user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($order = $result->fetch_assoc()) {
            $msg = "Tôi cần hỗ trợ đơn hàng này:\n";
            $msg .= "📦 Đơn hàng #{$order['id']}\n";
            $msg .= "👤 Tên người đặt: {$order['fullname']}\n";
            $msg .= "💰 Tổng tiền: " . number_format($order['total_amount']) . "đ\n";
            $msg .= "💳 Thanh toán: {$order['payment_method']}\n";
            $msg .= "📅 Ngày tạo: {$order['created_at']}";

            // Ghi vào DB
            $insert = $conn->prepare("INSERT INTO support_chat (user_id, sender, message) VALUES (?, 'customer', ?)");
            $insert->bind_param("is", $user_id, $msg);
            $insert->execute();

            // Gửi email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'volengocson19@gmail.com';
            $mail->Password = 'uwvpllwiedouugbd';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('volengocson19@gmail.com', 'Support Bot');
            $mail->addAddress('volengocson19@gmail.com');
            $mail->Subject = "Hỗ trợ đơn hàng từ khách hàng #$user_id";
            $mail->Body = $msg;
            $mail->send();
        }
    }
    // Không exit() để tiếp tục hiển thị giao diện chat
}

// ======== GỬI TIN NHẮN THƯỜNG =========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO support_chat (user_id, sender, message) VALUES (?, 'customer', ?)");
        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();

        // Gửi email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yourgmail@gmail.com';
        $mail->Password = 'your-app-password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourgmail@gmail.com', 'Support Bot');
        $mail->addAddress('volengocson19@gmail.com');
        $mail->Subject = "Khách hàng $user_id: Hỗ trợ";
        $mail->Body = $message;
        $mail->send();

        exit(); // AJAX không load lại trang
    }
}

// ======== XÓA CHAT =========
if (isset($_GET['clear'])) {
    $conn->query("DELETE FROM support_chat WHERE user_id = $user_id");
    header("Location: home.php");
    exit();
}

// ======== LOAD TIN NHẮN =========
if (isset($_GET['load'])) {
    $result = $conn->query("SELECT * FROM support_chat WHERE user_id = $user_id ORDER BY sent_at ASC");
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . ($row['sender'] === 'customer' ? "Bạn" : "Hỗ trợ") . ":</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";
    }
    exit();
}
?>

<!-- ======== HTML Giao diện chat ========= -->
<!DOCTYPE html>
<html>
<head>
    <title>Hỗ trợ trực tuyến</title>
    <link rel="stylesheet" href="../../assets/css/customer/chat.css?v=<?=time()?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h2>💬 Hỗ trợ trực tuyến</h2>
<div id="chat-box" style="height:300px; overflow-y:auto; border:1px solid #ccc; padding:10px;"></div>
<form id="chat-form" method="POST">
    <input type="text" id="message" name="message" placeholder="Nhập tin nhắn..." required>
    <button type="submit">Gửi</button>
</form>
<br>
<form method="get">
    <input type="hidden" name="clear" value="1">
    <button type="submit">⬅ Quay lại Trang chủ</button>
</form>

<script>
function loadMessages() {
    $.get("support_chat.php?load=1", function(data) {
        $("#chat-box").html(data);
    });
}

$("#chat-form").submit(function(e) {
    e.preventDefault();
    $.post("support_chat.php", { message: $("#message").val() }, function() {
        $("#message").val('');
        loadMessages();
    });
});

setInterval(loadMessages, 3000);
loadMessages();
</script>
</body>
</html>
