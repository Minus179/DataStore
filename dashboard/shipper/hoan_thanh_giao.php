<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    header("Location: ../../login/login.php");
    exit();
}

$shipper_id = $_SESSION['shipper_id'] ?? $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];

    // Cập nhật trạng thái đơn sang "completed"
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed', delivered_at = NOW() WHERE id = ? AND shipper_id = ?");
    $stmt->bind_param("ii", $order_id, $shipper_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Hoàn thành đơn hàng thành công! Chúc mừng bạn. 🎉";
    } else {
        echo "Cập nhật trạng thái thất bại.";
    }
    $stmt->close();
}

$conn->close();
?>
