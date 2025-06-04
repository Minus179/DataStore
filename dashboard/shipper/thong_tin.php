<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    echo "Bạn cần đăng nhập với vai trò shipper.";
    exit();
}

$shipper_id = $_SESSION['shipper_id'] ?? $_SESSION['user_id'];

$user = [
    'name' => '',
    'avatar' => 'default.png',
    'phone' => '',
    'email' => '',
    'vehicle' => '',
];

if ($stmt = $conn->prepare("SELECT name, avatar, phone, email, vehicle FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $shipper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0
