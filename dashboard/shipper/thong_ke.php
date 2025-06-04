<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    echo "Bạn cần đăng nhập với vai trò shipper.";
    exit();
}

$shipper_id = $_SESSION['shipper_id'] ?? $_SESSION['user_id'];

// Lấy thống kê: số đơn hàng đã giao, tổng doanh thu (nếu shipper hưởng phần trăm)
// Giả sử shipper hưởng 10% trên tổng tiền đơn hàng

$sql = "SELECT COUNT(*) AS total_orders, SUM(total_price) AS total_revenue
        FROM orders
        WHERE shipper_id = ? AND status = 'completed'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shipper_id);
$stmt->execute();
$stmt->bind_result($total_orders, $total_revenue);
$stmt->fetch();
$stmt->close();

$commission_rate = 0.10; // 10%
$total_commission = $total_revenue * $commission_rate;
?>

<h2>📊 Thống kê giao hàng</h2>
<ul>
    <li>Tổng đơn hàng đã giao: <strong><?php echo $total_orders ?? 0; ?></strong></li>
    <li>Tổng doanh thu đơn hàng: <strong><?php echo number_format($total_revenue ?? 0); ?>đ</strong></li>
    <li>Hoa hồng của bạn (10%): <strong><?php echo number_format($total_commission ?? 0); ?>đ</strong></li>
</ul>
<p>Giữ vững phong độ, bạn sẽ lên top thôi! 🔥</p>

<?php $conn->close(); ?>
