<?php
session_start();

try {
    // Kiểm tra đăng nhập & phân quyền
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
        header("Location: ../../login/login.php");
        exit();
    }

    require_once __DIR__ . '/../../includes/db.php';

    // Lấy owner_id từ session
    $owner_id = $_SESSION['user_id'];

    // Lấy danh sách món hàng (đã chuẩn bị câu lệnh)
    $stmt = $pdo->prepare('SELECT * FROM menu_items ORDER BY created_at DESC');
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy thông tin quán theo owner_id
    $stmt = $pdo->prepare("SELECT * FROM store_info WHERE owner_id = ?");
    $stmt->execute([$owner_id]);
    $store_info = $stmt->fetch(PDO::FETCH_ASSOC);

    // Nếu chưa có thông tin, tạo mảng mặc định để tránh lỗi
    if (!$store_info) {
        $store_info = [
            'name' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'description' => '',
            'avatar' => 'default_store.png'
        ];
    }
} catch (PDOException $e) {
    // Bật debug hoặc log lỗi tùy môi trường dev/prod
    die("Lỗi kết nối hoặc truy vấn cơ sở dữ liệu: " . $e->getMessage());
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
        <?php if ($_SESSION['role'] === 'owner'): ?>
            <form method="POST" action="update_store_info.php" enctype="multipart/form-data" class="add-item-form">
                <div class="form-row">
                    <div class="form-col">
                        <label for="storeName"><i class="fas fa-store"></i> Tên quán</label>
                        <input type="text" id="storeName" name="name" value="<?= htmlspecialchars($store_info['name']) ?>" required>
                    </div>
                    <div class="form-col">
                        <label>Địa chỉ</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($store_info['address']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label>Điện thoại</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($store_info['phone']) ?>" required>
                    </div>
                    <div class="form-col">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($store_info['email']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col" style="flex-basis: 100%;">
                        <label>Mô tả</label>
                        <textarea name="description" rows="4" placeholder="Mô tả ngắn về quán"><?= htmlspecialchars($store_info['description']) ?></textarea>
                    </div>
                </div>

                <div class="form-row" style="align-items: center;">
                    <div class="form-col">
                        <label>Ảnh đại diện quán (Avatar)</label>
                        <input type="file" name="avatar" accept="image/*">
                    </div>
                    <div class="form-col" style="text-align: center;">
                        <label>Avatar hiện tại:</label><br>
                        <?php if (!empty($store_info['avatar'])): ?>
                            <img src="../../assets/images/store/<?= htmlspecialchars($store_info['avatar']) ?>" alt="Store Avatar" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 1px solid #ccc;">
                        <?php else: ?>
                            <p>Chưa có ảnh đại diện</p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" style="margin-top: 15px; padding: 8px 16px; cursor: pointer;">Cập nhật thông tin</button>
            </form>
        <?php else: ?>
            <!-- Khách hàng chỉ được xem -->
            <p><strong>Tên quán:</strong> <?= htmlspecialchars($store_info['name']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($store_info['address']) ?></p>
            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($store_info['phone']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($store_info['email']) ?></p>
            <p><strong>Mô tả:</strong> <?= htmlspecialchars($store_info['description']) ?></p>
            <?php if (!empty($store_info['avatar'])): ?>
                <p><strong>Avatar:</strong><br>
                    <img src="../../assets/images/store/<?= htmlspecialchars($store_info['avatar']) ?>" alt="Store Avatar" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 1px solid #ccc;">
                </p>
            <?php else: ?>
                <p><strong>Avatar:</strong> Chưa có ảnh đại diện</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>


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
                window.location.href = '../../login/login.php';
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
