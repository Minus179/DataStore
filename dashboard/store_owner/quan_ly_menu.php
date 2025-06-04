<?php 
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    header("Location: ../../login/login.php");
    exit();
}

 require_once __DIR__ . '/../../includes/db.php';

$stmt = $pdo->query('SELECT * FROM menu_items ORDER BY created_at DESC');
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy thông tin quán
$stmtStore = $pdo->prepare('SELECT * FROM store_info WHERE owner_id = ? LIMIT 1');
$stmtStore->execute([$_SESSION['user_id']]);
$store = $stmtStore->fetch(PDO::FETCH_ASSOC) ?: ['name'=>'', 'address'=>'', 'phone'=>'', 'email'=>'', 'description'=>'', 'avatar'=>''];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Chủ quán / Admin</title>
    <link rel="stylesheet" href="../../assets/css/store_owner/home.css?v=<?=time()?>" />
    <link rel="stylesheet" href="../../assets/css/store_owner/home_1.css?v=<?=time()?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        .filter-tab {
            background-color: #e0f2f1;
            color: #388e85;
            border: 2px solid #388e85;
            padding: 10px 16px;
            margin-right: 10px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .filter-tab:hover {
            background-color: #c7e3e0;
        }
        .filter-tab.active {
            background-color: #388e85;
            color: #fff;
            border-color: #388e85;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>

<main class="main-content">

<header class="top-header">
    <button class="tab-button active" onclick="showTab('all', this)">Tất cả món</button>
    <button class="tab-button" onclick="location.href='add_mon.php'">Thêm món</button>
    <button class="tab-button" onclick="location.href='edit_mon.php'">Sửa / Xóa món</button>
    <button class="tab-button" onclick="showTab('profile_store', this)">Cập nhật thông tin</button>
</header>


    <section id="content-area">
        <!-- Tab: Danh sách món -->
        <div id="all" class="tab-content">
            <h3>Danh sách món đang bán</h3>
            <div style="margin-bottom: 20px;">
                <button onclick="filterItems('all')" class="tab filter-tab active">🍽️ Tất cả</button>
                <button onclick="filterItems('food')" class="tab filter-tab">🥘 Món ăn</button>
                <button onclick="filterItems('drink')" class="tab filter-tab">🥤 Đồ uống</button>
            </div>
            <?php if (empty($items)): ?>
                <p>Chưa có món nào được thêm.</p>
            <?php else: ?>
                <div class="menu-grid" id="menuGrid">
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

        <!-- Tab: Cập nhật thông tin quán -->
<!-- Tab: Cập nhật thông tin quán -->
            <div id="profile_store" class="tab-content" style="display:none;">
                <h3>Cập nhật thông tin quán</h3>
                <?php
                // Lấy thông tin quán hiện tại
                $stmtStore = $pdo->prepare('SELECT * FROM store_info WHERE owner_id = ? LIMIT 1');
                $stmtStore->execute([$_SESSION['user_id']]);
                $store = $stmtStore->fetch(PDO::FETCH_ASSOC);

                if (!$store) {
                    $store = ['name' => '', 'address' => '', 'phone' => '', 'description' => '', 'avatar' => ''];
                }
                ?>
                <form action="update_store_info.php" method="post" class="form-section" enctype="multipart/form-data">
                    <label>Tên quán:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($store['name']) ?>" required />

                    <label>Địa chỉ:</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($store['address']) ?>" required />

                    <label>Số điện thoại:</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($store['phone']) ?>" required pattern="\d+" title="Chỉ nhập số" />

                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($store['email'] ?? '') ?>" required />

                    <label>Mô tả quán:</label>
                    <textarea name="description" rows="4" placeholder="Mô tả về quán..." required><?= htmlspecialchars($store['description']) ?></textarea>

                    <label>Ảnh đại diện quán:</label>
                    <?php if (!empty($store['avatar'])): ?>
                        <div>
                            <img src="/DataStore/assets/images/store/<?= htmlspecialchars($store['avatar']) ?>" alt="Avatar quán" width="120" style="border-radius: 10px; margin-bottom: 10px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="avatar" accept="image/*" />

                    <button type="submit">Cập nhật thông tin</button>
                </form>
            </div>

        <!-- Alert popup -->
        <div id="alert-box" style="display:none; position:fixed; bottom:20px; right:20px; background-color:#4caf50; color:#fff; padding:10px 20px; border-radius:5px; box-shadow:0 2px 5px rgba(0,0,0,0.2); z-index:1000; font-size:14px;"></div>
    </section>
</main>

<script src="../../assets/js/store_owner/quan_ly_menu.js?v=<?=time()?>"></script>
<script>
function showAlert(message, duration = 3000) {
    const alertBox = document.getElementById('alert-box');
    alertBox.textContent = message;
    alertBox.style.display = 'block';
    setTimeout(() => {
        alertBox.style.display = 'none';
    }, duration);
}

function filterItems(type) {
    const cards = document.querySelectorAll('.menu-card');
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => tab.classList.remove('active'));
    document.querySelector(`.filter-tab[onclick*="${type}"]`).classList.add('active');

    cards.forEach(card => {
        card.style.display = (type === 'all' || card.dataset.type === type) ? 'flex' : 'none';
    });
}
function showTab(tabId, button = null) {
    // Ẩn tất cả các tab
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.style.display = 'none');

    // Xoá active ở tất cả các button
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(btn => btn.classList.remove('active'));

    // Hiện tab cần hiển thị
    document.getElementById(tabId).style.display = 'block';

    // Đặt class active cho nút tương ứng nếu có
    if (button) {
        button.classList.add('active');
    }
}
</script>
</body>
</html>
