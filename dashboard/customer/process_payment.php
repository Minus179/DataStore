<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: bill.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu POST
$order_code = $_POST['order_code'] ?? '';
$total_price = $_POST['total_price'] ?? 0;
$payment_method = $_POST['payment_method'] ?? '';

if (empty($order_code) || $total_price <= 0 || !in_array($payment_method, ['cash', 'bank_transfer'])) {
    die("Dữ liệu thanh toán không hợp lệ.");
}

// Lấy chi tiết giỏ hàng để lưu đơn
$query = "
    SELECT c.item_id, c.quantity, m.price
    FROM cart c
    JOIN menu_items m ON c.item_id = m.id
    WHERE c.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Giỏ hàng của bạn đang trống, không thể tạo đơn hàng.");
}

// Bắt đầu transaction
$conn->begin_transaction();

try {
    // 1. Thêm đơn hàng vào bảng orders
    $insert_order = "INSERT INTO orders (order_code, user_id, total_price, payment_method, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $status = ($payment_method === 'cash') ? 'Chờ xử lý' : 'Chờ thanh toán'; // trạng thái đơn
    $order_stmt = $conn->prepare($insert_order);
    $order_stmt->bind_param("sidsi", $order_code, $user_id, $total_price, $payment_method, $status);
    // Nếu `status` kiểu string, sửa bind_param từ 'i' thành 's'. Cần check kiểu cột DB nhé!
    // Giả sử status varchar thì sửa thành 's'
    $order_stmt->bind_param("sisss", $order_code, $user_id, $total_price, $payment_method, $status);
    $order_stmt->execute();

    $new_order_id = $conn->insert_id;

    // 2. Thêm chi tiết đơn hàng vào order_items
    $insert_item = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
    $item_stmt = $conn->prepare($insert_item);

    while ($row = $result->fetch_assoc()) {
        $item_id = $row['item_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $item_stmt->bind_param("iiid", $new_order_id, $item_id, $quantity, $price);
        $item_stmt->execute();
    }

    // 3. Xóa giỏ hàng hiện tại
    $delete_cart = "DELETE FROM cart WHERE user_id = ?";
    $del_stmt = $conn->prepare($delete_cart);
    $del_stmt->bind_param("i", $user_id);
    $del_stmt->execute();

    // Commit transaction
    $conn->commit();

    // Xóa order_id session để tránh tạo đơn trùng
    unset($_SESSION['order_id']);

    // Redirect đến trang cảm ơn hoặc thông báo thành công
    header("Location: thank_you.php?order_code=" . urlencode($order_code));
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Đã có lỗi xảy ra khi xử lý đơn hàng: " . $e->getMessage());
}

$conn->close();
?>
