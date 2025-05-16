<?php
session_start();

// Kiểm tra đăng nhập & phân quyền
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../includes/db.php';

// Lấy danh sách món hàng từ DB
$stmt = $pdo->query('SELECT * FROM menu_items ORDER BY created_at DESC');
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Thông tin quán giả lập (có thể lấy từ DB nếu có bảng info quán)
$store_info = [
    'name' => 'Quán Ngọc Sơn Food',
    'address' => '123, Quy Nhơn, Gia Lai',
    'phone' => '0909 123 456',
    'email' => 'contact@ngocsonfood.vn',
    'description' => 'Quán chuyên phục vụ các món ăn và thức uống tươi ngon, chuẩn vị Việt.'
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Chủ quán / Admin</title>

    <!-- CSS chính -->
    <link rel="stylesheet" href="../../assets/css/store_owner/home.css?v=<?=time()?>" />
    <!-- CSS bổ sung cho header và main -->
    <link rel="stylesheet" href="../../assets/css/store_owner/home_1.css?v=<?=time()?>" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>

<main class="main-content">

    <!-- Header ngang trên đầu -->
    <header class="top-header">
        <button class="tab-button active" data-target="all">Tất cả món</button>
        <button class="tab-button" data-target="food">Món ăn</button>
        <button class="tab-button" data-target="drink">Món nước</button>
        <button class="tab-button" data-target="info">Thông tin quán</button>
        <button class="tab-button" id="logout-button">Đăng xuất</button>
    </header>

    <!-- Nội dung động -->
    <section id="content-area">
        <!-- Tab: Tất cả món -->
        <div id="all" class="tab-content">
            <h3>Danh sách món đang bán</h3>
            <?php if (empty($items)): ?>
                <p>Chưa có món nào được thêm.</p>
            <?php else: ?>
                <div class="menu-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="menu-card" data-type="<?= htmlspecialchars($item['type']) ?>">
                            <img src="../../assets/images/<?= htmlspecialchars($item['type']) ?>/<?= htmlspecialchars($item['image_path']) ?>"
                                 alt="<?= htmlspecialchars($item['name']) ?>" />
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab: Món ăn -->
        <div id="food" class="tab-content" style="display:none;">
            <h3>Món ăn</h3>
            <?php
            $food_items = array_filter($items, fn($item) => $item['type'] === 'food');
            if (empty($food_items)) {
                echo '<p>Không có món ăn nào.</p>';
            } else {
                echo '<div class="menu-grid">';
                foreach ($food_items as $item) {
                    echo '<div class="menu-card" data-type="food">';
                    echo '<img src="../../assets/images/food/' . htmlspecialchars($item['image_path']) . '" alt="' . htmlspecialchars($item['name']) . '">';
                    echo '<h4>' . htmlspecialchars($item['name']) . '</h4>';
                    echo '<p>' . number_format($item['price'], 0, ',', '.') . '₫</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <!-- Tab: Món nước -->
        <div id="drink" class="tab-content" style="display:none;">
            <h3>Món nước</h3>
            <?php
            $drink_items = array_filter($items, fn($item) => $item['type'] === 'drink');
            if (empty($drink_items)) {
                echo '<p>Không có món nước nào.</p>';
            } else {
                echo '<div class="menu-grid">';
                foreach ($drink_items as $item) {
                    echo '<div class="menu-card" data-type="drink">';
                    echo '<img src="../../assets/images/drink/' . htmlspecialchars($item['image_path']) . '" alt="' . htmlspecialchars($item['name']) . '">';
                    echo '<h4>' . htmlspecialchars($item['name']) . '</h4>';
                    echo '<p>' . number_format($item['price'], 0, ',', '.') . '₫</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <!-- Tab: Thông tin quán -->
        <div id="info" class="tab-content" style="display:none;">
            <h3>Thông tin quán</h3>
            <div class="info-section">
                <p><strong>Tên quán:</strong> <?= htmlspecialchars($store_info['name']) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($store_info['address']) ?></p>
                <p><strong>Điện thoại:</strong> <?= htmlspecialchars($store_info['phone']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($store_info['email']) ?></p>
                <p><strong>Mô tả:</strong> <?= htmlspecialchars($store_info['description']) ?></p>
            </div>
        </div>
    </section>
</main>

<script>
    // Xử lý chuyển tab nội dung
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Nút đăng xuất
            if (button.id === 'logout-button') {
                // Gửi request logout rồi redirect (có thể làm ajax hoặc đơn giản)
                // Ở đây redirect thẳng, bạn nhớ tạo file logout.php xử lý session_destroy()
                window.location.href = '../../login/logout.php';
                return;
            }

            // Xóa active trên tất cả nút
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Ẩn tất cả nội dung tab
            tabContents.forEach(content => content.style.display = 'none');

            // Hiển thị nội dung tab được click
            button.classList.add('active');
            const target = button.getAttribute('data-target');
            document.getElementById(target).style.display = 'block';
        });
    });
</script>
</body>
</html>
