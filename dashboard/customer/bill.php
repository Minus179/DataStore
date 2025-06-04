<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin khách hàng
$user_query = "SELECT name, phone, address FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_info = $user_result->fetch_assoc();

// Lấy giỏ hàng user
$query = "
    SELECT c.item_id, c.quantity, m.name, m.price, m.type, m.image
    FROM cart c
    JOIN menu_items m ON c.item_id = m.id
    WHERE c.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Giỏ hàng trống. <a href='menu.php'>Quay lại mua hàng</a></p>";
    exit();
}

$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $total_price += $row['price'] * $row['quantity'];
}

// Tạo mã đơn hàng duy nhất
if (!isset($_SESSION['order_id'])) {
    $_SESSION['order_id'] = 'OD' . date('YmdHis') . rand(1000, 9999);
}
$order_id = $_SESSION['order_id'];
$time = date('H:i:s');
$date = date('d/m/Y');

// QR Code thông tin ngân hàng
$bank_account = '7865237919';
$bank_name = 'VCB';
$account_name = 'Vo Ngoc Son';
$amount = $total_price;
$content = $order_id;
$qr_data = "https://img.vietqr.io/image/VCB-{$bank_account}-compact2.png?amount={$amount}&addInfo={$content}&accountName=" . urlencode($account_name);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Thanh Toán - IT Startup</title>

<link rel="stylesheet" href="../../assets/css/customer/bill.css?v=<?=time()?>" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<script>
  function togglePaymentInfo() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const qrSection = document.getElementById('qr-section');
    if(method === 'bank_transfer') {
      qrSection.style.display = 'flex';
    } else {
      qrSection.style.display = 'none';
    }
  }

  window.addEventListener('DOMContentLoaded', () => {
    togglePaymentInfo();
    document.querySelectorAll('input[name="payment_method"]').forEach(el => {
      el.addEventListener('change', togglePaymentInfo);
    });
  });
</script>

</head>
<body>

<header>
  <div class="header-container">
    
    <h2 class="cart-title">💳 Thanh Toán</h2>
    <div class="placeholder"></div>
  </div>
</header>

 <div class="back-to-home">
   <a href="home.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
 </div>

<main class="container mx-auto py-8 px-4 max-w-4xl">

  <section class="mb-6">
    <h3 class="text-xl font-semibold mb-2">Thông tin khách hàng</h3>
    <p><strong>Tên:</strong> <?= htmlspecialchars($user_info['name']) ?></p>
    <p><strong>SĐT:</strong> <?= htmlspecialchars($user_info['phone']) ?></p>
    <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($user_info['address']) ?></p>
  </section>

  <section class="mb-6">
    <h3 class="text-xl font-semibold mb-2">Đơn hàng của bạn</h3>
    <table class="min-w-full bg-white rounded-2xl shadow overflow-hidden mb-4">
      <thead class="bg-red-700 text-white">
        <tr>
          <th class="py-3 px-5 text-left">Tên món</th>
          <th class="py-3 px-5 text-center">Số lượng</th>
          <th class="py-3 px-5 text-right">Giá tiền (₫)</th>
          <th class="py-3 px-5 text-right">Thành tiền (₫)</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $stmt->execute();
      $res = $stmt->get_result();
      while ($row = $res->fetch_assoc()):
        $item_total = $row['price'] * $row['quantity'];
      ?>
        <tr class="border-b border-gray-200 hover:bg-yellow-100 transition">
          <td class="p-3"><?= htmlspecialchars($row['name']) ?></td>
          <td class="p-3 text-center"><?= $row['quantity'] ?></td>
          <td class="p-3 text-right"><?= number_format($row['price'], 0, ',', '.') ?></td>
          <td class="p-3 text-right font-semibold text-green-700"><?= number_format($item_total, 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

    <div class="text-2xl font-bold text-red-700 text-right">
      Tổng tiền: <?= number_format($total_price, 0, ',', '.') ?>₫
    </div>
  </section>

  <section class="mb-6">
    <h3 class="text-xl font-semibold mb-2">Chọn phương thức thanh toán</h3>
    <form action="process_payment.php" method="post">
      <input type="hidden" name="order_code" value="<?= $order_id ?>">
      <input type="hidden" name="total_price" value="<?= $total_price ?>">

      <label class="mr-6">
        <input type="radio" name="payment_method" value="cash" checked /> Tiền mặt
      </label>

      <label>
        <input type="radio" name="payment_method" value="bank_transfer" /> Chuyển khoản
      </label>

      <div id="qr-section">
        <div class="payment-details">
          <h4>Thông tin chuyển khoản</h4>
          <p><strong>Ngân hàng:</strong> Vietcombank</p>
          <p><strong>Chủ tài khoản:</strong> VÕ NGỌC SƠN</p>
          <p><strong>Số tài khoản:</strong> 7865237919</p>
          <p><strong>Nội dung chuyển khoản:</strong> <?= $order_id ?></p>
        </div>
        <div class="qr-code-container">
          <img src="<?= $qr_data ?>" alt="QR Vietcombank" class="qr-code" />
        </div>
      </div>

      <div class="mt-6 text-right">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full font-semibold">
          Xác nhận thanh toán
        </button>
      </div>
    </form>
  </section>

</main>

<?php include '../../includes/footer_2.php'; ?>

<script>
  togglePaymentInfo();
</script>

</body>
</html>

<?php
$conn->close();
?>
