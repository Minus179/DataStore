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

    // Cập nhật trạng thái đơn sang "delivering"
    $stmt = $conn->prepare("UPDATE orders SET status = 'delivering' WHERE id = ? AND shipper_id = ?");
    $stmt->bind_param("ii", $order_id, $shipper_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Đơn hàng đã được bắt đầu giao. Cố lên nha! 🚀";
    } else {
        echo "Không thể cập nhật trạng thái đơn.";
    }
    $stmt->close();
}

$conn->close();
?>
