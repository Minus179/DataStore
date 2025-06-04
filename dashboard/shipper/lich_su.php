<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    echo "Bạn cần đăng nhập với vai trò shipper.";
    exit();
}

$shipper_id = $_SESSION['shipper_id'] ?? $_SESSION['user_id'];

// Lấy danh sách đơn đã giao xong (status = 'completed')
$sql = "SELECT o.id, o.created_at, o.delivered_at, u.name as customer_name, o.address, o.total_price
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        WHERE o.shipper_id = ? AND o.status = 'completed'
        ORDER BY o.delivered_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shipper_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>🕒 Lịch sử giao hàng</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table border="1" width="100%" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Địa chỉ</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Ngày giao</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo number_format($row['total_price']); ?>đ</td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['delivered_at']); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Bạn chưa có lịch sử giao hàng nào. Vậy thì chạy nhanh lên! 💨</p>
<?php endif; ?>

<?php
$stmt->close();
$conn->close();
?>
