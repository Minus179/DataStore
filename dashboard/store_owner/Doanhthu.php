<?php
session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'store_owner') {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../includes/db.php';  // file kết nối mysqli

$user_id = $_SESSION['user_id'];

// Lấy danh sách quán của chủ quán (đảm bảo bảng stores hoặc tên bảng đúng nhé)
$sqlStores = "SELECT id, name FROM stores WHERE owner_id = ?";
$stmtStores = $conn->prepare($sqlStores);
$stmtStores->bind_param("i", $user_id);
$stmtStores->execute();
$resultStores = $stmtStores->get_result();
$stores = $resultStores->fetch_all(MYSQLI_ASSOC);
$stmtStores->close();

$storeRevenues = [];

foreach ($stores as $store) {
    $sqlRevenue = "SELECT IFNULL(SUM(total_price), 0) AS total_revenue 
                   FROM orders 
                   WHERE store_id = ? AND status = 'Đã thanh toán'";
    $stmtRevenue = $conn->prepare($sqlRevenue);
    $stmtRevenue->bind_param("i", $store['id']);
    $stmtRevenue->execute();
    $resultRevenue = $stmtRevenue->get_result();
    $rowRevenue = $resultRevenue->fetch_assoc();
    $storeRevenues[] = [
        'store_name' => $store['name'],
        'total_revenue' => $rowRevenue['total_revenue'] ?? 0
    ];
    $stmtRevenue->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Doanh thu các quán của bạn</title>
    <link rel="stylesheet" href="../../assets/css/store_owner/don_hang.css?v=<?=time()?>" />
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        h1 { color: #333; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 60%; margin: 0 auto; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .no-data { text-align: center; font-style: italic; color: #666; margin-top: 50px;}
    </style>
</head>
<body>

<h1>Doanh thu các quán của bạn</h1>

<?php if (count($storeRevenues) === 0): ?>
    <p class="no-data">Bạn chưa có quán nào hoặc chưa có doanh thu.</p>
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
                    <td><?= number_format($sr['total_revenue'], 0, ',', '.') ?> đ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
