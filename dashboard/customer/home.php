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

// Lấy thông tin người dùng (avatar, tên)
$userQuery = "SELECT name, avatar FROM users WHERE id = ?";
$stmtUser = $conn->prepare($userQuery);
$stmtUser->bind_param("i", $_SESSION['user_id']);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$userData = $userResult->fetch_assoc();

$username = $userData['name'] ?? 'Khách hàng';
$avatar = $userData['avatar'] ?? 'default-avatar.jpg';

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
    <meta charset="UTF-8" />
    <title>Trang chính - Khách hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../assets/css/customer/home.css?v=<?= time() ?>" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
</head>
<body>

<header>
    <div class="header-container">
        <div class="user-info">
            <img src="/DataStore/assets/images/picture/<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar" />
            <p>Xin chào, <span class="username"><?= htmlspecialchars($username) ?></span>!</p>
        </div>
        <div class="header-actions">
            <a href="profile.php" class="action-btn">Tài khoản</a>
            <a href="logout.php" class="action-btn">Đăng xuất</a>
        </div>
    </div>
</header>

<main>
    <!-- Tìm kiếm -->
    <form action="timkiem.php" method="GET" class="search-container" id="searchForm">
        <div class="search-bar">
            <input type="text" name="query" class="search-input" placeholder="Nhập món ăn cần tìm..." onkeypress="submitOnEnter(event)" onclick="submitForm()" />
            <button type="submit" class="search-button" onclick="submitForm()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <script>
        function submitOnEnter(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                submitForm();
            }
        }
        function submitForm() {
            document.getElementById("searchForm").submit();
        }
    </script>

    <!-- Banner khuyến mãi -->
    <div class="banner-container">
        <div class="banner">
            <?php
            $banners = ['banner_1.jpg', 'banner_2.jpg', 'banner_3.jpg', 'banner_4.jpg', 'banner_5.jpg'];
            foreach ($banners as $banner) {
                echo '<img src="../../assets/images/banner/' . $banner . '" alt="Banner khuyến mãi" />';
            }
            ?>
        </div>
        <div class="marquee-container">
            <marquee behavior="scroll" direction="left">
                🔊 Khuyến mãi sập sàn, cơ hội không thể bỏ lỡ! Hãy nhanh tay đặt đơn ngay hôm nay để nhận vô vàn ưu đãi hấp dẫn, quà tặng bất ngờ và những phần quà siêu hot chỉ có trong thời gian có hạn!
            </marquee>
        </div>
    </div>

    <!-- Tính năng -->
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
            <a href="Game/mini_game.php" class="feature">
                <i class="fas fa-gamepad"></i>
                <span>Mini game</span>
            </a>
        </div>
    </div>

    <!-- Bộ sưu tập món ăn -->
    <div class="collections-wrapper">
        <div class="food-collection-section">
            <section class="collections">
                <h2>Món ăn hôm nay</h2>
                <div class="collection-list">
                    <?php
                    $stmt_food = $conn->prepare("SELECT * FROM menu_items WHERE type = 'food' LIMIT 15");
                    $stmt_food->execute();
                    $result_food = $stmt_food->get_result();

                    if ($result_food->num_rows === 0) {
                        echo "<p>Hiện tại không có món ăn nào.</p>";
                    } else {
                        while ($item = $result_food->fetch_assoc()) {
                            ?>
                            <div class="collection-card">
                                <img src="/DataStore/assets/images/food/<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="100" />
                                <p><?= htmlspecialchars($item['name']) ?></p>
                                <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                                <div class="button-group">
                                    <form action="add_to_cart.php" method="post">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>" />
                                        <a href="items_detail.php?id=<?= $item['id'] ?>" class="btn-detail">
                                            <i class="fas fa-info-circle"></i> Xem chi tiết
                                        </a>
                                        <button type="submit" class="btn-add-cart">
                                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Bộ sưu tập món nước -->
    <div class="collections-wrapper">
        <div class="drink-collection-section">
            <section class="collections">
                <h2>Món nước hôm nay</h2>
                <div class="collection-list">
                    <?php
                    $stmt_drink = $conn->prepare("SELECT * FROM menu_items WHERE type = 'drink' LIMIT 15");
                    $stmt_drink->execute();
                    $result_drink = $stmt_drink->get_result();

                    if ($result_drink->num_rows === 0) {
                        echo "<p>Hiện tại không có món nước nào.</p>";
                    } else {
                        while ($item = $result_drink->fetch_assoc()) {
                            ?>
                            <div class="collection-card">
                                <img src="/DataStore/assets/images/drink/<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="100" />
                                <p><?= htmlspecialchars($item['name']) ?></p>
                                <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                                <div class="button-group">
                                    <form action="add_to_cart.php" method="post">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>" />
                                        <a href="items_detail.php?id=<?= $item['id'] ?>" class="btn-detail">
                                            <i class="fas fa-info-circle"></i> Xem chi tiết
                                        </a>
                                        <button type="submit" class="btn-add-cart">
                                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
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
            <div class="footer-column">
                <h3>Đồ uống</h3>
                <ul>
                    <li><a href="#">Trà sữa</a></li>
                    <li><a href="#">Cà phê</a></li>
                    <li><a href="#">Nước ép</a></li>
                    <li><a href="#">Soda & đá xay</a></li>
                    <li><a href="#">Nước ngọt</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Thông tin liên hệ</h3>
                <ul>
                    <li>Email: datastore.food@gmail.com</li>
                    <li>Hotline: 1900 1234</li>
                    <li>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</li>
                </ul>
            </div>
        </div>
    </footer>

    <div class="bottom-navigation">
        <a href="home.php" class="nav-icon active"><i class="fas fa-home"></i></a>
        <a href="menu.php" class="nav-icon"><i class="fas fa-utensils"></i></a>
        <a href="cart.php" class="nav-icon">
            <i class="fas fa-shopping-cart"></i>
            <?php if ($cartCount > 0): ?>
                <span class="cart-count"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="profile.php" class="nav-icon"><i class="fas fa-user"></i></a>
    </div>
</main>

</body>
</html>
