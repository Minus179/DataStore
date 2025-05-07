<?php
session_start();

// Chuyển hướng nếu chưa đăng nhập hoặc không phải khách hàng
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login/login.php");
    exit();
}

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "datastore_food");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Gán giá trị mặc định cho username
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'Khách hàng'; // Gán tên mặc định
}

// Lấy số lượng sản phẩm trong giỏ hàng
$cartCount = 0;
$cartQuery = "SELECT COUNT(*) AS item_count FROM cart WHERE user_id = ?";
$stmtCart = $conn->prepare($cartQuery);
$stmtCart->bind_param("i", $_SESSION['user_id']);
$stmtCart->execute();
$cartResult = $stmtCart->get_result();
if ($cartData = $cartResult->fetch_assoc()) {
    $cartCount = $cartData['item_count'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chính - Khách hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/customer/dashboard_customer.css?v=<?=time()?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<header>
    <div class="header-container">
        <div class="user-info">
            <p>Xin chào, <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>!</p>
        </div>
        <div class="header-actions">
            <a href="profile.php" class="action-btn">Tài khoản</a>
            <a href="logout.php" class="action-btn">Đăng xuất</a>
        </div>
    </div>
</header>

<main>
    
<!-- Thanh tìm kiếm -->
<div class="search-bar">
    <form action="timkiem.php" method="get">
        <input 
            type="text" 
            name="query" 
            placeholder="Tìm món ăn yêu thích..." 
            class="search-input" 
            required
            id="search-input"
            onclick="window.location='timkiem.php';"
        >
        <button type="submit" class="search-button">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>


<!-- Banner khuyến mãi -->
<div class="banner-container">
    <div class="banner">
        <?php
        $banners = ['banner_1.jpg', 'banner_2.jpg', 'banner_3.jpg', 'banner_4.jpg', 'banner_5.jpg'];
        foreach ($banners as $banner) {
            echo '<img src="../../assets/images/banner/' . $banner . '" alt="Banner khuyến mãi">';
        }
        ?>
    </div>
    <div class="marquee-container">
        <marquee behavior="scroll" direction="left">🔊 Khuyến mãi sập sàn, cơ hội không thể bỏ lỡ! Hãy nhanh tay đặt đơn ngay hôm nay để nhận vô vàn ưu đãi hấp dẫn, quà tặng bất ngờ và những phần quà siêu hot chỉ có trong thời gian có hạn!</marquee>
    </div>
</div>

    <!-- Tính năng -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="features-scroll">
  <div class="features-list">
    <a href="menu.php" class="feature">
      <i class="fas fa-utensils"></i>
      <span>Menu</span>
    </a>
    <a href="cart.php" class="feature">
      <i class="fas fa-shopping-cart"></i>
      <span>Giỏ hàng</span>
    </a>
    <a href="theo_doi_vi_tri.php" class="feature">
      <i class="fas fa-map-marker-alt"></i>
      <span>Theo dõi</span>
    </a>
    <a href="mini_games.php" class="feature">
      <i class="fas fa-gamepad"></i>
      <span>Mini game</span>
    </a>
  </div>
</div>


    <!-- Bộ sưu tập món ăn -->
    <section class="collections">
        <h2>Món ăn hôm nay</h2>
        <div class="collection-list">
            <?php
            $stmt_food = $conn->prepare("SELECT * FROM menu_items WHERE category = 'food' LIMIT 5");
            $stmt_food->execute();
            $result_food = $stmt_food->get_result();

            if ($result_food->num_rows === 0) {
                echo "<p>Hiện tại không có món ăn nào.</p>";
            } else {
                while ($item = $result_food->fetch_assoc()) {
                    ?>
                    <div class="collection-card">
                    <img src="/DataStore/assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <p><?= htmlspecialchars($item['name']) ?></p>
                        <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn-add-cart">Thêm vào giỏ</button>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </section>

    <!-- Bộ sưu tập món nước -->
    <section class="collections">
        <h2>Món nước hôm nay</h2>
        <div class="collection-list">
            <?php
            $stmt_drink = $conn->prepare("SELECT * FROM menu_items WHERE category = 'drink' LIMIT 5");
            $stmt_drink->execute();
            $result_drink = $stmt_drink->get_result();

            if ($result_drink->num_rows === 0) {
                echo "<p>Hiện tại không có món nước nào.</p>";
            } else {
                while ($item = $result_drink->fetch_assoc()) {
                    ?>
                    <div class="collection-card">
                    <img src="/DataStore/assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <p><?= htmlspecialchars($item['name']) ?></p>
                        <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn-add-cart">Thêm vào giỏ</button>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </section>

    <footer class="footer">
  <div class="footer-container">
    <!-- Cột: Món ăn -->
    <div class="footer-column">
      <h3>Thực đơn</h3>
      <ul>
        <li><a href="#">Đồ ăn vặt</a></li>
        <li><a href="#">Cơm - Mì - Phở</a></li>
        <li><a href="#">Lẩu & Nướng</a></li>
        <li><a href="#">Đồ chay</a></li>
        <li><a href="#">Đặc sản vùng miền</a></li>
      </ul>
    </div>

    <!-- Cột: Đồ uống -->
    <div class="footer-column">
      <h3>Đồ uống</h3>
      <ul>
        <li><a href="#">Trà sữa</a></li>
        <li><a href="#">Cà phê</a></li>
        <li><a href="#">Nước ép</a></li>
        <li><a href="#">Soda & đá xay</a></li>
        <li><a href="#">Đồ uống healthy</a></li>
      </ul>
    </div>

    <!-- Cột: Dịch vụ -->
    <div class="footer-column">
      <h3>Dịch vụ</h3>
      <ul>
        <li><a href="#">Giao hàng tận nơi</a></li>
        <li><a href="#">Đặt bàn trước</a></li>
        <li><a href="#">Khuyến mãi</a></li>
        <li><a href="#">Tích điểm đổi quà</a></li>
        <li><a href="#">Mini game thưởng</a></li>
      </ul>
    </div>

    <!-- Cột: Liên hệ -->
    <div class="footer-column">
      <h3>Liên hệ</h3>
      <ul>
        <li><a href="#">Giới thiệu</a></li>
        <li><a href="#">Hỗ trợ khách hàng</a></li>
        <li><a href="#">Điều khoản sử dụng</a></li>
        <li><a href="#">Chính sách bảo mật</a></li>
        <li><a href="#">Fanpage chính thức</a></li>
      </ul>
    </div>
  </div>

  <!-- Thông tin công ty -->
  <div class="footer-bottom">
    <p>© 2025 DATASTORE_FOOD - Hệ thống đặt đồ ăn và nước uống tiện lợi</p>
    <p>Địa chỉ: Số 244 Cống Quỳnh, P. Phạm Ngũ Lão, Q.1, TP.HCM</p>
  </div>
</footer>

    <!-- Điều hướng dưới cùng -->
    <nav class="bottom-nav">
        <a href="home.php"><i class="fas fa-home"></i><span> Trang chủ</span></a>
        <a href="cart.php">
            <i class="fas fa-shopping-cart"></i>
            <span> Giỏ hàng</span>
            <?php if ($cartCount > 0): ?>
                <span class="cart-count"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="profile.php"><i class="fas fa-user"></i><span> Tài khoản</span></a>
    </nav>
</main>

<script src="../../assets/js/customer.js"></script>
</body>
</html>

<?php
$conn->close();
?>
