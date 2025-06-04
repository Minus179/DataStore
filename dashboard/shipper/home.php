<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shipper') {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../includes/db.php';

$shipper_id = $_SESSION['shipper_id'] ?? $_SESSION['user_id'];

$user = [
    'name' => 'Shipper',
    'avatar' => 'default.png'
];

if ($stmt = $conn->prepare("SELECT name, avatar FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $shipper_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shipper Dashboard - DataStore</title>
    <link rel="stylesheet" href="../../assets/css/shipper/home.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="user-info">
            <img src="../../assets/avatar/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar">
            <h3>Xin chào, <?php echo htmlspecialchars($user['name']); ?></h3>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#" class="nav-link" data-page="don_can_giao.php">📦 Đơn cần giao</a></li>
                <li><a href="#" class="nav-link" data-page="don_dang_giao.php">🚚 Đơn đang giao</a></li>
                <li><a href="#" class="nav-link" data-page="lich_su.php">🕒 Lịch sử giao</a></li>
                <li><a href="#" class="nav-link" data-page="thong_ke.php">📊 Thống kê</a></li>
                <li><a href="#" class="nav-link" data-page="thong_tin.php">👤 Thông tin cá nhân</a></li>
                <li><a href="../../login/login.php">🚪 Đăng xuất</a></li>
            </ul>
        </nav>
    </aside>

    <!-- ✅ THÊM khối nội dung chính để load page AJAX -->
    <main id="main-content" class="main-content">
        <h2>Chào mừng bạn đến với trang shipper!</h2>
        <p>Chọn một mục bên trái để bắt đầu giao hàng nào! 💪</p>
    </main>
</div>

<script>
$(document).ready(function () {
    $('.nav-link').click(function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            $('#main-content').load(page);
        }
    });
});
</script>
</body>
</html>
