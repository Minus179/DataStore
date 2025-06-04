<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    header("Location: ../../login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$shipper_id = $_SESSION['user_id'];
$msg = "";

// Xử lý khi shipper nhấn nhận đơn
if (isset($_POST['accept_order'])) {
    $order_id = intval($_POST['order_id']);

    // Cập nhật đơn hàng: gán shipper_id và đổi trạng thái
    $update_sql = "UPDATE orders SET shipper_id = ?, status = 'Đang giao', is_assigned = 1 WHERE id = ? AND status = 'Chờ xử lý'";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("ii", $shipper_id, $order_id);
    $stmt_update->execute();

    if ($stmt_update->affected_rows > 0) {
        $msg = "Nhận đơn thành công!";
    } else {
        $msg = "Đơn này đã được nhận hoặc không tồn tại!";
    }
}

// Lấy danh sách đơn hàng chưa được giao
$sql = "SELECT o.id, o.order_code, o.address, o.total_price, o.payment_method, o.status, o.created_at, u.name AS customer_name, u.phone AS customer_phone
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.status = 'Chờ xử lý' AND (o.is_assigned = 0 OR o.is_assigned IS NULL)
        ORDER BY o.created_at ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đơn cần giao - Shipper</title>
    <link rel="stylesheet" href="../../assets/css/customer/bill.css?v=<?= time() ?>" />
    <style>
      table { width: 100%; border-collapse: collapse; margin-top: 20px; }
      th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
      th { background-color: #f44336; color: white; }
      button { padding: 6px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
      button:hover { background-color: #45a049; }
      .msg {
          margin: 10px 0;
          padding: 10px;
          background-color: #d4edda;
          color: #155724;
          border: 1px solid #c3e6cb;
          border-radius: 4px;
          opacity: 1;
          transition: opacity 0.5s ease;
          text-align: center;
          font-weight: 600;
      }
    </style>
</head>
<body>
    <header>
        <h2>🚚 Đơn hàng cần giao</h2>
    </header>

    <main class="container">
      <?php if (!empty($msg)) : ?>
        <div class="msg" id="notification"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <?php if ($result->num_rows > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Mã đơn</th>
              <th>Khách hàng</th>
              <th>SĐT</th>
              <th>Địa chỉ giao</th>
              <th>Tổng tiền (₫)</th>
              <th>Phương thức TT</th>
              <th>Ngày đặt</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['order_code']) ?></td>
              <td><?= htmlspecialchars($row['customer_name']) ?></td>
              <td><?= htmlspecialchars($row['customer_phone']) ?></td>
              <td><?= htmlspecialchars($row['address']) ?></td>
              <td><?= number_format($row['total_price'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['payment_method']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
              <td>
                <form method="post" style="display:inline">
                  <input type="hidden" name="order_id" value="<?= $row['id'] ?>" />
                  <button type="submit" name="accept_order">Nhận đơn</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>Hiện tại không có đơn hàng nào cần giao.</p>
      <?php endif; ?>
    </main>

    <script>
      window.onload = function() {
        const msgDiv = document.getElementById('notification');
        if (msgDiv) {
          setTimeout(() => {
            msgDiv.style.opacity = '0';
          }, 2000);
          setTimeout(() => {
            location.reload();
          }, 2500);
        }
      };
    </script>
</body>
</html>

<?php $conn->close(); ?>
