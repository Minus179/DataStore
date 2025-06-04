<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    echo "Bạn cần đăng nhập với vai trò shipper.";
    exit();
}

$shipper_id = $_SESSION['shipper_id'] ?? $_SESSION['user_id'];

// Lấy đơn đã nhận và đang trong trạng thái giao (ví dụ status = 'accepted' hoặc 'delivering')
$sql = "SELECT o.id, o.created_at, u.name as customer_name, o.address, o.total_price, o.status
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        WHERE o.shipper_id = ? AND o.status IN ('accepted', 'delivering')
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shipper_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>🚚 Đơn đang giao</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table border="1" width="100%" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Địa chỉ giao</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo number_format($row['total_price']); ?>đ</td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] === 'accepted'): ?>
                        <form method="post" action="bat_dau_giao.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Bắt đầu giao</button>
                        </form>
                    <?php elseif ($row['status'] === 'delivering'): ?>
                        <form method="post" action="hoan_thanh_giao.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Hoàn thành</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Không có đơn hàng nào đang giao. Nghỉ tí thôi! 🛵💨</p>
<?php endif; ?>

<?php
$stmt->close();
$conn->close();
?>
