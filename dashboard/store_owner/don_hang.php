<?php
session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'store_owner') {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../includes/db.php';

// Lấy tab hiện tại từ URL, mặc định 'new'
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'new';

// Thống kê tổng số đơn và tổng tiền cho tab paid, confirmed và history
$stats = [
    'count' => 0,
    'total' => 0,
];

if ($tab === 'paid' || $tab === 'confirmed') {
    // Lấy trạng thái tương ứng tab
    $status_for_stat = $tab === 'paid' ? 'Đã thanh toán' : 'Đã xác nhận';
    $stmt_stat = $conn->prepare("SELECT COUNT(*) as count, SUM(total_price) as total FROM orders WHERE status = ?");
    $stmt_stat->bind_param("s", $status_for_stat);
    $stmt_stat->execute();
    $result_stat = $stmt_stat->get_result();
    if ($row_stat = $result_stat->fetch_assoc()) {
        $stats['count'] = (int)$row_stat['count'];
        $stats['total'] = (float)$row_stat['total'];
    }
    $stmt_stat->close();
} elseif ($tab === 'history') {
    // Lịch sử gồm Đã thanh toán + Đã xác nhận
    $stmt_stat = $conn->prepare("SELECT COUNT(*) as count, SUM(total_price) as total FROM orders WHERE status IN ('Đã thanh toán', 'Đã xác nhận')");
    $stmt_stat->execute();
    $result_stat = $stmt_stat->get_result();
    if ($row_stat = $result_stat->fetch_assoc()) {
        $stats['count'] = (int)$row_stat['count'];
        $stats['total'] = (float)$row_stat['total'];
    }
    $stmt_stat->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['order_id']) && !empty($_POST['action'])) {
    $order_id = (int)$_POST['order_id'];
    $action = $_POST['action'];

    $status = null;
    if ($action === 'confirm_payment') {
        $status = 'Đã thanh toán';
    } elseif ($action === 'confirm_order') {
        $status = 'Đã xác nhận';
    }

    if ($status !== null) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();

        // Redirect để tránh submit lại form khi refresh
        header("Location: " . $_SERVER['PHP_SELF'] . "?tab=" . $tab);
        exit();
    }
}

// Tạo câu truy vấn theo tab
$status_condition = '';
switch ($tab) {
    case 'new':
        $status_condition = "WHERE o.status IN ('Chờ thanh toán', 'Chờ xử lý')";
        break;
    case 'paid':
        $status_condition = "WHERE o.status = 'Đã thanh toán'";
        break;
    case 'confirmed':
        $status_condition = "WHERE o.status = 'Đã xác nhận'";
        break;
    case 'history':
        $status_condition = "WHERE o.status IN ('Đã thanh toán', 'Đã xác nhận')";
        break;
    default:
        $status_condition = "WHERE o.status IN ('Chờ thanh toán', 'Chờ xử lý')";
        break;
}

$query = "
    SELECT o.id AS order_id, o.order_code, o.total_price, o.payment_method, o.status, o.created_at, u.name AS customer_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    $status_condition
    ORDER BY o.created_at DESC
";

$orders = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Đơn hàng - Dashboard Chủ Quán</title>
    <link rel="stylesheet" href="../../assets/css/store_owner/don_hang.css?v=<?=time()?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>

<section class="main-content">
    <div class="top-header">
        <button class="tab-button <?= $tab === 'new' ? 'active' : '' ?>" data-tab="new">Đơn hàng mới</button>
        <button class="tab-button <?= $tab === 'paid' ? 'active' : '' ?>" data-tab="paid">Banking</button>
        <button class="tab-button <?= $tab === 'confirmed' ? 'active' : '' ?>" data-tab="confirmed">Money</button>
        <button class="tab-button <?= $tab === 'history' ? 'active' : '' ?>" data-tab="history">Lịch sử</button>
    </div>

    <?php if (in_array($tab, ['paid', 'confirmed', 'history'])): ?>
        <div class="stats-summary" style="margin-bottom: 15px; padding: 10px; background: #f0f0f0; border-radius: 5px;">
            <strong>Tổng đơn hàng:</strong> <?= $stats['count'] ?> đơn <br>
            <strong>Tổng tiền:</strong> <?= number_format($stats['total'], 0, ',', '.') ?>đ
        </div>
    <?php endif; ?>


    <div class="order-list">
        <?php if ($orders->num_rows === 0): ?>
            <p>Không có đơn hàng nào ở tab này.</p>
        <?php else: ?>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <div class="order-box">
                    <h3>Mã đơn: <?= htmlspecialchars($order['order_code']) ?> | Khách: <?= htmlspecialchars($order['customer_name']) ?></h3>
                    <p><strong>Tổng tiền:</strong> <?= number_format($order['total_price'], 0, ',', '.') ?>đ</p>
                    <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
                    <p><strong>Thời gian đặt:</strong> <?= htmlspecialchars($order['created_at']) ?></p>

                    <ul>
                        <?php
                        $stmt = $conn->prepare("
                            SELECT m.name, oi.quantity
                            FROM order_items oi
                            JOIN menu_items m ON oi.item_id = m.id
                            WHERE oi.order_id = ?
                        ");
                        $stmt->bind_param("i", $order['order_id']);
                        $stmt->execute();
                        $items = $stmt->get_result();
                        while ($item = $items->fetch_assoc()):
                        ?>
                            <li><?= htmlspecialchars($item['name']) ?> x <?= (int)$item['quantity'] ?></li>
                        <?php endwhile; $stmt->close(); ?>
                    </ul>

                    <?php if ($order['status'] === 'Chờ thanh toán' && $order['payment_method'] === 'bank_transfer'): ?>
                        <form method="POST" onsubmit="return confirm('Xác nhận đã nhận chuyển khoản?');">
                            <input type="hidden" name="order_id" value="<?= (int)$order['order_id'] ?>">
                            <input type="hidden" name="action" value="confirm_payment">
                            <button type="submit" class="btn-confirm">✅ Đã nhận chuyển khoản</button>
                        </form>
                    <?php elseif ($order['status'] === 'Chờ xử lý' && $order['payment_method'] === 'cash'): ?>
                        <form method="POST" onsubmit="return confirm('Xác nhận đơn hàng?');">
                            <input type="hidden" name="order_id" value="<?= (int)$order['order_id'] ?>">
                            <input type="hidden" name="action" value="confirm_order">
                            <button type="submit" class="btn-confirm">✅ Xác nhận đơn hàng</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>

<script>
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.getAttribute('data-tab');
            window.location.href = '?tab=' + tab;
        });
    });
</script>
</body>
</html>

<?php $conn->close(); ?>
