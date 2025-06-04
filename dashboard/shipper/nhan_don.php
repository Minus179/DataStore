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

    // Cập nhật đơn: gán shipper_id, đổi status sang "accepted"
    $stmt = $conn->prepare("UPDATE orders SET shipper_id = ?, status = 'accepted' WHERE id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $shipper_id, $order_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Bạn đã nhận đơn thành công! 🚀";
    } else {
        echo "Nhận đơn thất bại, có thể đơn đã được nhận rồi hoặc không tồn tại.";
    }
    $stmt->close();
}

$conn->close();
?>
