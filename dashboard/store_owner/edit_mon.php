<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['store_owner', 'admin'])) {
    header("Location: ../../login/login.php");
    exit();
}

 require_once __DIR__ . '/../../includes/db.php';

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: edit_mon.php');
    exit();
}

$stmt = $pdo->query('SELECT * FROM menu_items ORDER BY created_at DESC');
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý món - Sửa & Xóa</title>
     <link rel="stylesheet" href="../../assets/css/store_owner/home.css?v=<?=time()?>" />
    <link rel="stylesheet" href="../../assets/css/store_owner/home_1.css?v=<?=time()?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-...your_hash..." crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>
<main class="main-content">
    <header class="top-header">
        <button class="tab-button" onclick="location.href='quan_ly_menu.php'">Tất cả món</button>
        <button class="tab-button" onclick="location.href='add_mon.php'">Thêm món</button>
        <button class="tab-button" onclick="location.href='edit_mon.php'">Sửa / Xóa món</button>
        <button class="tab-button" data-target="profile_store">Cập nhật thông tin</button>
    </header>
    <h2>Quản lý món - Sửa hoặc Xóa</h2>
    <?php if (empty($items)): ?>
        <p>Không có món nào.</p>
    <?php else: ?>
        <div class="menu-grid">
            <?php foreach ($items as $item): ?>
                <div class="menu-card">
                    <img src="../../assets/images/<?= htmlspecialchars($item['type']) ?>/<?= htmlspecialchars($item['image_path']) ?>"
                         alt="<?= htmlspecialchars($item['name']) ?>">
                    <h4><?= htmlspecialchars($item['name']) ?></h4>
                    <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                    <div class="menu-card-actions">
                        <div class="action-wrapper">
                            <button class="mini-btn btn-edit">Sửa</button>
                        </div>
                         <div class="action-wrapper">
                        <button class="mini-btn btn-delete" data-id="<?= $item['id'] ?>">Xóa</button>
                    </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            if (confirm('Bạn có chắc muốn xoá món này không?')) {
                window.location.href = '../edit_mon.php?delete=' + id;
            }
        });
    });
});
</script>
</body>
</html>
