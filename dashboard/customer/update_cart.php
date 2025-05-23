<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli("localhost", "root", "", "datastore_food");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Xóa món
    if (!empty($_POST['remove'])) {
        $remove_items = $_POST['remove'];
        $placeholders = implode(',', array_fill(0, count($remove_items), '?'));
        $types = str_repeat('i', count($remove_items));

        $stmtDelete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND item_id IN ($placeholders)");
        $stmtDelete->bind_param('i' . $types, $user_id, ...$remove_items);
        $stmtDelete->execute();
    }

    // Cập nhật số lượng
    if (!empty($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $item_id => $quantity) {
            $item_id = intval($item_id);
            $quantity = intval($quantity);
            if ($quantity > 0) {
                $stmtUpdate = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?");
                $stmtUpdate->bind_param("iii", $quantity, $user_id, $item_id);
                $stmtUpdate->execute();
            }
        }
    }

    $conn->close();

    header("Location: cart.php");
    exit();
} else {
    header("Location: cart.php");
    exit();
}
?>
