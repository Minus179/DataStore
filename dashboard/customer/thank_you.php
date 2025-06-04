<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

$order_code = $_GET['order_code'] ?? '';

if (empty($order_code)) {
    echo "<h2>Không tìm thấy mã đơn hàng.</h2>";
    exit();
}

// Kết nối DB
$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin đơn hàng theo order_code và user_id để bảo mật
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT order_code, total_price, payment_method, status, created_at FROM orders WHERE order_code = ? AND user_id = ?");
$stmt->bind_param("si", $order_code, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2>Không tìm thấy đơn hàng phù hợp.</h2>";
    exit();
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Thanh toán thành công - DATASTORE_FOOD</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
    <h1 class="text-3xl font-bold mb-4 text-green-700">Cảm ơn bạn đã đặt hàng!</h1>
    <p class="mb-2 text-gray-700">Mã đơn hàng của bạn là:</p>
    <p class="text-xl font-mono mb-6 text-gray-900"><?php echo htmlspecialchars($order['order_code']); ?></p>

    <p class="mb-1"><strong>Tổng tiền:</strong> <?php echo number_format($order['total_price'], 0, ',', '.') ?>₫</p>
    <p class="mb-1"><strong>Phương thức thanh toán:</strong> <?php echo ($order['payment_method'] === 'cash') ? 'Tiền mặt' : 'Chuyển khoản'; ?></p>
    <p class="mb-4"><strong>Trạng thái đơn hàng:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
    <p class="text-sm text-gray-600 mb-6">Ngày đặt hàng: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>

    <a href="home.php" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full font-semibold">
        Quay về trang chủ
    </a>
</div>

</body>
</html>

<?php
$conn->close();
?>
