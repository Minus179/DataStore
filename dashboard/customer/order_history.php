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

$stmt = $conn->prepare("SELECT order_code, total_price, payment_method, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Lịch sử đơn hàng - DATASTORE_FOOD</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-5xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Lịch sử đơn hàng của bạn</h1>

        <?php if ($result->num_rows === 0): ?>
            <p class="text-gray-600">Bạn chưa có đơn hàng nào.</p>
        <?php else: ?>
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-green-100">
                        <th class="border border-gray-300 px-4 py-2">Mã đơn hàng</th>
                        <th class="border border-gray-300 px-4 py-2">Tổng tiền</th>
                        <th class="border border-gray-300 px-4 py-2">Phương thức thanh toán</th>
                        <th class="border border-gray-300 px-4 py-2">Trạng thái</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày đặt</th>
                        <th class="border border-gray-300 px-4 py-2">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-green-50">
                        <td class="border border-gray-300 px-4 py-2 font-mono text-sm"><?php echo htmlspecialchars($order['order_code']); ?></td>
                        <td class="border border-gray-300 px-4 py-2 text-right"><?php echo number_format($order['total_price'], 0, ',', '.') ?>₫</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <?php 
                            echo ($order['payment_method'] === 'cash') ? 'Tiền mặt' : 'Chuyển khoản'; 
                            ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center font-semibold -
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <a href="bill_success.php?order_code=<?php echo urlencode($order['order_code']); ?>" 
                               class="text-green-600 hover:text-green-800 font-semibold underline">
                               Xem
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="mt-6 text-right">
            <a href="home.php" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full font-semibold">
                Quay về trang chủ
            </a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
