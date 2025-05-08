<?php
// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

// Lấy ID sản phẩm từ URL
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($item_id <= 0) {
    die('Sản phẩm không hợp lệ.');
}

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) {
    die('Không tìm thấy sản phẩm.');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm: <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/customer/items_detail.css?v=<?= time() ?>">
</head>
<body>
    <header>
        <h1>Chi tiết sản phẩm</h1>
    </header>

    <main class="product-detail">
        <div class="product-image">
            <img src="/DataStore/assets/images/detail/<?= htmlspecialchars($product['type']) ?>/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="price"><?= number_format($product['price'], 0, ',', '.') ?>₫</p>
            <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="item_id" value="<?= $product['id'] ?>">
                <button type="submit" class="btn-add-cart">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Về Chúng Tôi</h3>
                <ul>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Liên hệ</a></li>
                    <li><a href="#">Tuyển dụng</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Hỗ Trợ</h3>
                <ul>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Kết Nối</h3>
                <ul class="social-icons">
                    <li><a href="#"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Đăng Ký Nhận Tin</h3>
                <form class="subscribe-form">
                    <input type="email" placeholder="Nhập email của bạn" />
                    <button type="submit">Đăng ký</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Công ty TNHH ABC. Đã đăng ký bản quyền.</p>
        </div>
    </footer>
</body>
</html>
