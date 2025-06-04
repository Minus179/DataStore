<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = intval($_POST['item_id']);

    $conn = new mysqli("localhost", "root", "", "datastore_food");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Kiểm tra món này đã có trong giỏ chưa?
    $checkQuery = "SELECT quantity FROM cart WHERE user_id = ? AND item_id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("ii", $user_id, $item_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Món đã có, tăng số lượng lên 1
        $row = $resultCheck->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;

        $updateQuery = "UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("iii", $new_quantity, $user_id, $item_id);
        $stmtUpdate->execute();
    } else {
        // Món chưa có, thêm mới quantity = 1
        $insertQuery = "INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, 1)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ii", $user_id, $item_id);
        $stmtInsert->execute();
    }

    $conn->close();

    // Quay lại trang trước đó (home hoặc menu)
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header("Location: home.php");
    exit();
}
?>
