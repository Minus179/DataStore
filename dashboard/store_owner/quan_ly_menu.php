<?php
session_start();

// Kiểm tra đăng nhập & phân quyền
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../includes/db.php'; // db.php khai báo $pdo = new PDO(...)

// Lấy danh sách món hàng từ DB
$stmt = $pdo->query('SELECT * FROM menu_items ORDER BY created_at DESC');
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Chủ quán / Admin</title>

    <!-- CSS chính -->
    <link rel="stylesheet" href="../../assets/css/store_owner/home.css?v=<?=time()?>" />
    <link rel="stylesheet" href="../../assets/css/store_owner/home_1.css?v=<?=time()?>" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>

<main class="main-content">

    <header class="top-header">
        <button class="tab-button active" data-target="all">Tất cả món</button>
        <button class="tab-button" data-target="food">Món ăn</button>
        <button class="tab-button" data-target="drink">Món nước</button>
        <button class="tab-button" data-target="them">Thêm món</button>
        <button class="tab-button" data-target="sua">Sửa món</button>
        <button class="tab-button" data-target="xoa">Xóa món</button>
    </header>

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

        <!-- Tab: Thêm món -->
        <div id="them" class="tab-content" style="display:none;">
            <h3>Thêm món mới</h3>
            <form action="edit/them.php" method="post" enctype="multipart/form-data" class="form-section">
                <label>Tên món:</label>
                <input type="text" name="name" required />

                <label>Giá:</label>
                <input type="number" name="price" required min="0" />

                <label>Loại:</label>
                <select name="type" required>
                    <option value="food">Món ăn</option>
                    <option value="drink">Món nước</option>
                </select>

                <label>Mô tả món:</label>
                <textarea name="description" rows="3" placeholder="Mô tả món ăn..." required></textarea>

                <label>Còn hàng không?</label>
                <select name="available" required>
                    <option value="1">Còn hàng</option>
                    <option value="0">Hết hàng</option>
                </select>

                <label>Ảnh chính:</label>
                <input type="file" name="image_main" accept="image/*" required />

                <label>Ảnh mô tả 1:</label>
                <input type="file" name="image_desc1" accept="image/*" />

                <label>Ảnh mô tả 2:</label>
                <input type="file" name="image_desc2" accept="image/*" />

                <button type="submit">Thêm</button>
            </form>
        </div>

<!-- Tab: Sửa món -->
<div id="sua" class="tab-content" style="display:none;">
    <h3>Chọn món để sửa</h3>
    <div class="menu-grid" id="edit-menu-grid" style="position: relative; display: flex; flex-wrap: wrap; gap: 20px;">
        <?php foreach ($items as $item): ?>
            <div class="menu-card edit-card" 
                data-id="<?= htmlspecialchars($item['id'], ENT_QUOTES) ?>" 
                data-name="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>"
                data-price="<?= htmlspecialchars($item['price'], ENT_QUOTES) ?>" 
                data-type="<?= htmlspecialchars($item['type'], ENT_QUOTES) ?>"
                data-description="<?= htmlspecialchars($item['description'], ENT_QUOTES) ?>"
                data-image="<?= htmlspecialchars($item['image_path'], ENT_QUOTES) ?>"
                style="width: 200px; transition: transform 0.3s ease;">
                
                <img src="../../assets/images/<?= htmlspecialchars($item['type'], ENT_QUOTES) ?>/<?= htmlspecialchars($item['image_path'], ENT_QUOTES) ?>"
                     alt="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>" />
                
                <h4><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></h4>
                <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                <button class="edit-btn">Sửa</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

            <!-- Form sửa món ẩn mặc định -->
            <div id="edit-form-container" style="display:none; margin-top: 30px; border: 1px solid #ccc; padding: 20px; max-width: 500px;">
                <h3>Chỉnh sửa món</h3>
                <form id="edit-form" action="edit/sua.php" method="post" enctype="multipart/form-data" class="form-section">
                    <input type="hidden" name="id" id="edit-id" />
                    <label>Tên món:</label>
                    <input type="text" name="name" id="edit-name" required />

                    <label>Giá:</label>
                    <input type="number" name="price" id="edit-price" required min="0" />

                    <label>Loại:</label>
                    <select name="type" id="edit-type" required>
                        <option value="food">Món ăn</option>
                        <option value="drink">Món nước</option>
                    </select>

                    <label>Mô tả món:</label>
                    <textarea name="description" id="edit-description" rows="3" placeholder="Mô tả món ăn..." required></textarea>

                    <label>Còn hàng không?</label>
                    <select name="available" id="edit-available" required>
                        <option value="1">Còn hàng</option>
                        <option value="0">Hết hàng</option>
                    </select>

                    <label>Ảnh chính hiện tại:</label><br/>
                    <img id="edit-current-image" src="" alt="Ảnh hiện tại" style="max-width: 150px; margin-bottom: 10px;" /><br/>

                    <label>Thay đổi ảnh chính (nếu có):</label>
                    <input type="file" name="image_main" accept="image/*" />

                    <button type="submit">Lưu thay đổi</button>
                    <button type="button" id="edit-cancel-btn" style="margin-left: 10px;">Hủy</button>
                </form>
            </div>
        </div>

    <!-- Tab: Xóa món -->
    <div id="xoa" class="tab-content" style="display:none;">
        <h3>Chọn món để xóa</h3>
        <div class="menu-grid">
            <?php foreach ($items as $item): ?>
                <div class="menu-card" data-id="<?= $item['id'] ?>">
                    <img src="../../assets/images/<?= htmlspecialchars($item['type']) ?>/<?= htmlspecialchars($item['image_path']) ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>" />
                    <h4><?= htmlspecialchars($item['name']) ?></h4>
                    <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                    <button class="delete-btn">Xóa</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </section>

</main>

<script src="../../assets/js/store_owner/quan_ly_menu.js?v=<?=time()?>"></script>

</body>
</html>
