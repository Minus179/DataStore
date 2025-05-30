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

// Lấy thông tin khách hàng từ bảng users
$user_query = "SELECT name, phone, address FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_info = $user_result->fetch_assoc();


// Lấy giỏ hàng user
$query = "
    SELECT c.item_id, c.quantity, m.name, m.price, m.image_path, m.type
    FROM cart c
    JOIN menu_items m ON c.item_id = m.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Giỏ Hàng - IT Startup</title>
  <link rel="stylesheet" href="../../assets/css/customer/cart.css?v=<?=time()?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
 
  <!-- SEO meta -->
  <meta name="description" content="Xem và quản lý giỏ hàng của bạn tại IT Startup - Website bán đồ ăn và thức uống hiện đại.">
  <meta name="keywords" content="giỏ hàng, IT Startup, thức ăn, đồ uống, thanh toán">
  <meta name="author" content="IT Startup Team" />
  <meta name="theme-color" content="#dc2626" />

  <!-- Favicon -->
  <link rel="icon" href="../../assets/images/logo.png" type="image/png" />
</head>
<header>
  <div class="header-container">
    <h2 class="cart-title">🛒 Giỏ Hàng</h2>
    <div class="placeholder"></div> <!-- Dùng để cân bằng flex -->
  </div>
</header>

  <a href="../../dashboard/customer/home.php" class="back-btn" title="Quay lại trang trước">
        <i class="fas fa-arrow-left"></i>
  </a>

<main class="container mx-auto py-8 px-4 max-w-5xl">
  <?php if ($result->num_rows === 0): ?>
    <p class="text-center text-xl text-gray-600 mb-6">Giỏ hàng của bạn đang trống.</p>
    <div class="text-center">
      <a href="menu.php" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold">
        Tiếp tục mua hàng
      </a>
    </div>
  <?php else: ?>
    <form action="update_cart.php" method="post" class="mb-10">
      <table class="min-w-full bg-white rounded-2xl shadow overflow-hidden">
        <thead class="bg-red-700 text-white">
          <tr>
            <th class="py-3 px-5">Ảnh</th>
            <th class="py-3 px-5 text-left">Tên món</th>
            <th class="py-3 px-5">Giá</th>
            <th class="py-3 px-5">Số lượng</th>
            <th class="py-3 px-5 text-right">Thành tiền</th>
            <th class="py-3 px-5">Xóa</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()):
            $item_total = $row['price'] * $row['quantity'];
            $total_price += $item_total;
          ?>
          <tr class="border-b border-gray-200 hover:bg-yellow-100 transition">
            <td class="p-3 text-center">
              <img src="/DataStore/assets/images/<?= ($row['type'] === 'food' ? 'food' : 'drink') ?>/<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-20 h-20 object-cover rounded-lg mx-auto" />
            </td>
            <td class="p-3"><?= htmlspecialchars($row['name']) ?></td>
            <td class="p-3 text-center"><?= number_format($row['price'], 0, ',', '.') ?>₫</td>
            <td class="p-3 text-center">
              <input type="number" name="quantities[<?= $row['item_id'] ?>]" value="<?= $row['quantity'] ?>" min="1" class="w-16 text-center rounded border px-2 py-1" />
            </td>
            <td class="p-3 text-right font-semibold text-green-700"><?= number_format($item_total, 0, ',', '.') ?>₫</td>
            <td class="p-3 text-center">
              <input type="checkbox" name="remove[]" value="<?= $row['item_id'] ?>" title="Xóa món này" class="w-5 h-5" />
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="mt-6 flex justify-between items-center">
        <div class="text-2xl font-bold text-red-700">
          Tổng tiền: <?= number_format($total_price, 0, ',', '.') ?>₫
        </div>
        <div>
          <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-6 py-2 rounded-full font-semibold mr-4">
            Cập nhật giỏ hàng
          </button>
          <a href="bill.php" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full font-semibold">
            Thanh toán
          </a>
        </div>
      </div>
    </form>

<!-- Hiển thị thông tin khách hàng -->
<div class="mb-5">
  <label class="block font-semibold mb-2">Tên khách hàng:</label>
  <p class="px-4 py-3 bg-gray-100 rounded"><?= htmlspecialchars($user_info['name']) ?></p>
</div>

<div class="mb-5">
  <label class="block font-semibold mb-2">Số điện thoại:</label>
  <p class="px-4 py-3 bg-gray-100 rounded"><?= htmlspecialchars($user_info['phone']) ?></p>
</div>

<div class="mb-5">
  <label class="block font-semibold mb-2">Địa chỉ giao hàng:</label>
  <p class="px-4 py-3 bg-gray-100 rounded"><?= htmlspecialchars($user_info['address']) ?></p>
</div>

<!-- Input ẩn gửi dữ liệu về backend -->
<input type="hidden" name="customer_name" value="<?= htmlspecialchars($user_info['name']) ?>" />
<input type="hidden" name="customer_phone" value="<?= htmlspecialchars($user_info['phone']) ?>" />
<input type="hidden" name="customer_address" value="<?= htmlspecialchars($user_info['address']) ?>" />
<?php endif; ?>

</main>

<?php include '../../includes/footer_1.php'; ?>

</body>
</html>

<?php
$conn->close();
?>
