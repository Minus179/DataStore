<?php
session_start();
// Giả sử đã đăng nhập và $_SESSION['owner_id'] lưu id chủ quán
$owner_id = $_SESSION['owner_id'] ?? 1;  // ví dụ owner_id = 1

// Kết nối CSDL
$host = 'localhost';
$dbname = 'your_database';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy danh sách cửa hàng của chủ quán
    $sqlStores = "SELECT id, name FROM stores WHERE owner_id = :owner_id";
    $stmtStores = $pdo->prepare($sqlStores);
    $stmtStores->execute(['owner_id' => $owner_id]);
    $stores = $stmtStores->fetchAll(PDO::FETCH_ASSOC);

    // Mảng lưu doanh thu từng quán
    $storeRevenues = [];

    foreach ($stores as $store) {
        $sqlRevenue = "SELECT SUM(total_amount) as total_revenue FROM orders WHERE store_id = :store_id AND status = 'completed'";
        $stmtRevenue = $pdo->prepare($sqlRevenue);
        $stmtRevenue->execute(['store_id' => $store['id']]);
        $revenue = $stmtRevenue->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

        $storeRevenues[] = [
            'store_name' => $store['name'],
            'total_revenue' => $revenue
        ];
    }
} catch (PDOException $e) {
    die("Lỗi kết nối hoặc truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Doanh thu quán của bạn</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 50%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        tr:hover { background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Doanh thu các quán của bạn</h1>
    <?php if (count($storeRevenues) == 0): ?>
        <p>Bạn chưa có quán nào hoặc chưa có doanh thu.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Tên quán</th>
                    <th>Doanh thu (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($storeRevenues as $sr): ?>
                    <tr>
                        <td><?= htmlspecialchars($sr['store_name']) ?></td>
                        <td><?= number_format($sr['total_revenue'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
