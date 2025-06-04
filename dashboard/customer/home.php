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

    // Lấy thông tin người dùng (bao gồm ảnh đại diện và tên)
    $userQuery = "SELECT name, avatar FROM users WHERE id = ?";
    $stmtUser = $conn->prepare($userQuery);
    $stmtUser->bind_param("i", $_SESSION['user_id']);
    $stmtUser->execute();
    $userResult = $stmtUser->get_result();
    $userData = $userResult->fetch_assoc();

    // Gán giá trị mặc định cho name và avatar
    $username = isset($userData['name']) ? $userData['name'] : 'Khách hàng';  // Dùng 'name' thay vì 'username'
    $avatar = isset($userData['avatar']) ? $userData['avatar'] : 'default-avatar.jpg';  // Nếu không có ảnh đại diện, dùng ảnh mặc định

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
        <link rel="stylesheet" href="../../assets/css/customer/home.css?v=<?=time()?>">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    </head>
    <body>

    <header>
        <div class="header-container">
            <div class="user-info">
                <img src="../../assets/images/picture/<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar">
                <p>Xin chào, <span class="username"><?= htmlspecialchars($username) ?></span>!</p>
            </div>
            <div class="header-actions">
                <a href="../../login/login.php" class="action-btn">Đăng xuất</a>
            </div>
        </div>
    </header>

    <main>
      
    <!-- Tìm kiếm -->
    <form action="timkiem.php" method="GET" class="search-container" id="searchForm">
      <div class="search-bar">
        <input type="text" name="query" class="search-input" placeholder="Nhập món ăn cần tìm..." onkeypress="submitOnEnter(event)" onclick="submitForm()">
        <button type="submit" class="search-button" onclick="submitForm()">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>

    <script>
      // Hàm submit form khi nhấn Enter
      function submitOnEnter(event) {
        if (event.key === "Enter") {
          event.preventDefault();  // Ngăn chặn hành động mặc định của phím Enter
          submitForm();  // Gửi form
        }
      }

      // Hàm gửi form khi nhấn chuột vào ô nhập liệu hoặc nút tìm kiếm
      function submitForm() {
        document.getElementById("searchForm").submit();  // Gửi form
      }
    </script>
  
  <!-- Banner -->
  <div class="banner-container">
    <div class="banner">
      <?php
      $banners = ['banner_1.png', 'banner_2.jpg', 'banner_3.png', 'banner_4.jpg', 'banner_5.jpg'];
      // Nhân đôi mảng banner để tạo hiệu ứng lặp mượt
      $banners = array_merge($banners, $banners);
      foreach ($banners as $banner) {
          echo '<img src="../../assets/images/banner/' . $banner . '" alt="Banner khuyến mãi">';
      }
      ?>
    </div>
    <div class="marquee-container">
      <div class="marquee-text">
        <b>🔊 Khuyến mãi sập sàn, cơ hội không thể bỏ lỡ! Hãy nhanh tay đặt đơn ngay hôm nay để nhận vô vàn ưu đãi hấp dẫn, quà tặng bất ngờ và những phần quà siêu hot chỉ có trong thời gian có hạn!</b>
      </div>
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
        <a href="Game/mini_game.php" class="feature">
          <i class="fas fa-gamepad"></i>
          <span>Mini game</span>
        </a>
            <a href="voucher.php" class="feature">
      <i class="fas fa-ticket-alt"></i>
      <span>Voucher</span>
    </a>
    <a href="don_hang.php" class="feature">
      <i class="fas fa-history"></i>
      <span>Lịch sử đơn hàng</span>
    </a>
    <a href="danh_gia.php" class="feature">
      <i class="fas fa-star"></i>
      <span>Đánh giá</span>
    </a>
    <a href="support.php" class="feature">
      <i class="fas fa-headset"></i>
      <span>Hỗ trợ</span>
    </a>
    <a href="bao_cao_su_co.php" class="feature">
      <i class="fas fa-exclamation-triangle"></i>
      <span>Báo sự cố</span>
    </a>
    <a href="gop_y.php" class="feature">
      <i class="fas fa-comment-dots"></i>
      <span>Góp ý</span>
    </a>
    <a href="lienhe.php" class="feature">
      <i class="fas fa-phone-alt"></i>
      <span>Liên hệ</span>
    </a>
    <a href="support_chat.php" class="feature">
      <i class="fas fa-comments"></i>
      <span>Chat hỗ trợ</span>
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
                    <img src="/DataStore/assets/images/food/<?= htmlspecialchars($item['image_path']) ?>" 
                        alt="<?= htmlspecialchars($item['name']) ?>" 
                        width="100">
                    <p><?= htmlspecialchars($item['name']) ?></p>
                    <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                    <div class="button-group">
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
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
                    <img src="/DataStore/assets/images/drink/<?= htmlspecialchars($item['image_path']) ?>" 
                        alt="<?= htmlspecialchars($item['name']) ?>" 
                        width="100">
                    <p><?= htmlspecialchars($item['name']) ?></p>
                    <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                    <div class="button-group">
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <a href="items_detail.php?id=<?= $item['id'] ?>" class="btn-detail">
                                <i class="fas fa-info-circle"></i> Xem chi tiết
                            </a>
                            <button type="submit" class="btn-add-cart">
                                 <i class="fas fa-cart-plus"></i> 🛒 Thêm vào giỏ
                            </button>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        </div>
      </div>
    </section>
  </div>
  </div>
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
        <p>DATASTORE_FOOD - Hệ thống đặt đồ ăn và nước uống tiện lợi</p>
        <p>Địa chỉ: Số 490 Hoang Van Thu, P. Tran Phu, TP.Quy Nhon</p>
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