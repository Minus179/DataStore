    <?php
    session_start();
    if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
        header("Location: ../login/login.php");
        exit();
    }

    require_once '../../includes/db.php'; // file kết nối DB chuẩn của bạn
    require '../../includes/PHPMailer/src/PHPMailer.php';
    require '../../includes/PHPMailer/src/SMTP.php';
    require '../../includes/PHPMailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $user_id = $_SESSION['user_id'];

    // ===== Gửi tin nhắn tự động khi truy cập từ đơn hàng
    if (isset($_GET['order_id'])) {
        $order_id = (int)$_GET['order_id'];

        // Check xem đã gửi tin nhắn hỗ trợ đơn hàng này chưa
        $check = $conn->prepare("SELECT id FROM support_chat WHERE user_id = ? AND message LIKE ? LIMIT 1");
        $likeMsg = "%Đơn hàng #$order_id%";
        $check->bind_param("is", $user_id, $likeMsg);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            // Lấy thông tin đơn hàng
            $stmt = $conn->prepare("SELECT o.id, u.fullname, o.total_price, o.payment_method, o.created_at 
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
                $msg .= "💰 Tổng tiền: " . number_format($order['total_price'], 0, ',', '.') . "đ\n";
                $msg .= "💳 Thanh toán: {$order['payment_method']}\n";
                $msg .= "📅 Ngày tạo: {$order['created_at']}";

                // Ghi tin nhắn vào DB
                $insert = $conn->prepare("INSERT INTO support_chat (user_id, sender, message) VALUES (?, 'customer', ?)");
                $insert->bind_param("is", $user_id, $msg);
                $insert->execute();

                // Gửi email thông báo
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'volengocson19@gmail.com'; // Gmail bạn dùng
                    $mail->Password = 'uwvpllwiedouugbd';       // App password Gmail
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('volengocson19@gmail.com', 'Support Bot');
                    $mail->addAddress('volengocson19@gmail.com');
                    $mail->Subject = "Hỗ trợ đơn hàng từ khách hàng #$user_id";
                    $mail->Body = $msg;
                    $mail->send();
                } catch (Exception $e) {
                    // Có thể log $mail->ErrorInfo nếu cần
                }
            }
            $stmt->close();
        }
        $check->close();
    }

    // ===== Gửi tin nhắn hỗ trợ từ form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
        $message = trim($_POST['message']);
        if ($message !== '') {
            $stmt = $conn->prepare("INSERT INTO support_chat (user_id, sender, message) VALUES (?, 'customer', ?)");
            $stmt->bind_param("is", $user_id, $message);
            $stmt->execute();
            $stmt->close();

            // Gửi email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'volengocson19@gmail.com';
                $mail->Password = 'uwvpllwiedouugbd';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('volengocson19@gmail.com', 'Support Bot');
                $mail->addAddress('volengocson19@gmail.com');
                $mail->Subject = "Tin nhắn hỗ trợ từ khách hàng #$user_id";
                $mail->Body = $message;
                $mail->send();
            } catch (Exception $e) {
                // Log lỗi mail nếu cần
            }
        }
    }

    // ===== Lấy danh sách đơn hàng user
    $stmt = $conn->prepare("
        SELECT o.id AS order_id, o.order_code, o.total_price, o.payment_method, o.status, o.created_at, o.address
        FROM orders o
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders_result = $stmt->get_result();

    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <title>Lịch sử đơn hàng</title>
        <link rel="stylesheet" href="../../assets/css/customer/don_hang.css?v=<?= time() ?>" />
        <style>
            /* CSS popup + order card đơn giản đẹp */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f9f9f9;
                margin: 20px;
            }
            .container {
                max-width: 900px;
                margin: 0 auto;
                background: white;
                padding: 20px 30px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
                color: #222;
            }
            .back-btn {
                display: inline-block;
                margin-bottom: 15px;
                text-decoration: none;
                font-weight: 600;
                color: #444;
                background: #eee;
                padding: 8px 14px;
                border-radius: 6px;
                transition: background-color 0.25s ease;
            }
            .back-btn:hover {
                background: #ddd;
            }
            .order-card {
                cursor: pointer;
                border: 1px solid #ddd;
                padding: 18px 22px;
                margin: 12px 0;
                border-radius: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: box-shadow 0.3s ease;
                background: #fff;
            }
            .order-card:hover {
                box-shadow: 0 3px 12px rgba(0,0,0,0.1);
            }
            .order-info {
                max-width: 80%;
            }
            .order-info h3 {
                margin: 0 0 8px 0;
                font-weight: 700;
                color: #222;
            }
            .order-info p {
                margin: 4px 0;
                font-size: 14px;
                color: #555;
            }
            .status-label {
                padding: 4px 10px;
                border-radius: 15px;
                font-weight: 700;
                font-size: 13px;
                display: inline-block;
                text-transform: capitalize;
            }
            .status-pending {
                background: #ffe58f;
                color: #ad8b00;
            }
            .status-done {
                background: #d9f7be;
                color: #389e0d;
            }
            .status-failed {
                background: #ffa39e;
                color: #a8071a;
            }
            .help-button {
                background: #1890ff;
                border: none;
                color: white;
                padding: 10px 16px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            .help-button:hover {
                background: #146bc5;
            }
            /* Popup chat */
            #chat-popup {
                display: none;
                position: fixed;
                right: 30px;
                bottom: 30px;
                width: 320px;
                max-height: 450px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                overflow: hidden;
                flex-direction: column;
                z-index: 1000;
                font-family: Arial, sans-serif;
            }
            #chat-popup.active {
                display: flex;
            }
            #chat-header {
                background: #1890ff;
                color: white;
                padding: 14px 20px;
                font-weight: 700;
                display: flex;
                justify-content: space-between;
                align-items: center;
                user-select: none;
            }
            #chat-header span {
                font-size: 16px;
            }
            #chat-close {
                cursor: pointer;
                font-weight: 900;
                font-size: 20px;
                line-height: 20px;
            }
            #chat-messages {
                flex-grow: 1;
                padding: 15px 20px;
                overflow-y: auto;
                font-size: 14px;
                color: #333;
                background: #f1f1f1;
            }
            #chat-form {
                display: flex;
                border-top: 1px solid #ddd;
            }
            #chat-input {
                flex-grow: 1;
                border: none;
                padding: 12px 16px;
                font-size: 14px;
                border-radius: 0 0 0 12px;
                outline: none;
            }
            #chat-submit {
                background: #1890ff;
                border: none;
                color: white;
                padding: 0 18px;
                cursor: pointer;
                border-radius: 0 0 12px 0;
                font-weight: 700;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }
            #chat-submit:hover {
                background: #146bc5;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <a href="../index.php" class="back-btn">← Về trang chính</a>
        <h1>Lịch sử đơn hàng của bạn</h1>

        <?php if ($orders_result->num_rows === 0): ?>
            <p>Bạn chưa có đơn hàng nào.</p>
        <?php else: ?>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <div class="order-card" data-order-id="<?= htmlspecialchars($order['order_id']) ?>">
                    <div class="order-info">
                        <h3>Đơn hàng #<?= htmlspecialchars($order['order_id']) ?> - <?= htmlspecialchars($order['order_code']) ?></h3>
                        <p>Ngày tạo: <?= htmlspecialchars($order['created_at']) ?></p>
                        <p>Địa chỉ giao: <?= htmlspecialchars($order['address']) ?></p>
                        <p>Tổng tiền: <?= number_format($order['total_price'], 0, ',', '.') ?>đ</p>
                        <p>Phương thức thanh toán: <?= htmlspecialchars($order['payment_method']) ?></p>
                        <p>Trạng thái: 
                            <?php
                            $statusClass = 'status-pending';
                            if ($order['status'] === 'done') $statusClass = 'status-done';
                            elseif ($order['status'] === 'failed') $statusClass = 'status-failed';
                            ?>
                            <span class="status-label <?= $statusClass ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </p>
                    </div>
                    <button class="help-button" onclick="openChat(<?= $order['order_id'] ?>)">Hỗ trợ</button>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- Popup chat -->
    <div id="chat-popup">
        <div id="chat-header">
            <span>Hỗ trợ khách hàng</span>
            <span id="chat-close" onclick="closeChat()">×</span>
        </div>
        <div id="chat-messages">
            <p><i>Gửi tin nhắn để nhận hỗ trợ nhanh nhất.</i></p>
        </div>
        <form id="chat-form" method="post" onsubmit="return sendMessage();">
            <input type="text" id="chat-input" name="message" placeholder="Nhập tin nhắn..." autocomplete="off" required />
            <button type="submit" id="chat-submit">Gửi</button>
        </form>
    </div>

    <script>
        const chatPopup = document.getElementById('chat-popup');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        let currentOrderId = null;

        function openChat(orderId) {
            currentOrderId = orderId;
            chatPopup.classList.add('active');
            chatMessages.innerHTML = `<p><strong>Hỗ trợ đơn hàng #${orderId}</strong></p>`;
            chatInput.focus();
        }

        function closeChat() {
            chatPopup.classList.remove('active');
            currentOrderId = null;
            chatMessages.innerHTML = `<p><i>Gửi tin nhắn để nhận hỗ trợ nhanh nhất.</i></p>`;
            chatInput.value = '';
        }

        async function sendMessage() {
            const message = chatInput.value.trim();
            if (message === '') return false;

            // Gửi POST AJAX
            const formData = new FormData();
            formData.append('message', `Đơn hàng #${currentOrderId}: ${message}`);

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData,
                });
                if (!response.ok) throw new Error('Network error');
                chatMessages.innerHTML += `<p><strong>Bạn:</strong> ${message}</p>`;
                chatInput.value = '';
                chatInput.focus();
            } catch (err) {
                alert('Gửi tin nhắn thất bại, thử lại sau nhé.');
            }

            return false; // chặn form submit reload page
        }

        // Tự động mở popup nếu URL có ?order_id=xxx
        const params = new URLSearchParams(window.location.search);
        if (params.has('order_id')) {
            const orderId = params.get('order_id');
            openChat(orderId);
        }
    </script>
    </body>
    </html>
