<?php
session_start();
include("../../includes/db.php");

$searchResults = [];
$suggestions = [];

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Hàm lấy đường dẫn ảnh theo loại món, trả về default nếu không tồn tại ảnh
function getImagePath($type, $fileName) {
    $folder = ($type === 'food') ? 'food' : 'drink';
    $path = "../../assets/images/{$folder}/{$fileName}";
    return (!file_exists($path) || empty($fileName)) ? "../../assets/images/default.jpg" : $path;
}

// Xử lý tìm kiếm
if (!empty($_GET['search_term'])) {
    // Kiểm tra CSRF token cho GET (tuy ít cần nhưng làm cho chuẩn)
    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token không hợp lệ. Vui lòng tải lại trang.");
    }

    $searchTerm = trim($_GET['search_term']);
    $param = "%$searchTerm%";

    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE name LIKE ? OR description LIKE ?");
    if ($stmt) {
        $stmt->bind_param('ss', $param, $param);
        $stmt->execute();
        $result = $stmt->get_result();
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        die("Lỗi truy vấn: " . $conn->error);
    }
}

// Lấy món gợi ý phổ biến (popularity > 50)
$suggestionResult = $conn->query("SELECT * FROM menu_items WHERE popularity > 50 ORDER BY popularity DESC LIMIT 10");
if (!$suggestionResult) {
    die("Lỗi truy vấn: " . $conn->error);
}
$suggestions = $suggestionResult->fetch_all(MYSQLI_ASSOC);

// Danh mục món ăn kèm icon
$categories = [
    'Phở' => '🍲',
    'Bún' => '🍜',
    'Bánh Canh' => '🍲',
    'Cơm' => '🍚',  
    'Cà phê' => '☕',
    'Hủ Tiếu' => '🥣',
    'Bánh Mì' => '🥖',
    'Cháo' => '🍲',
    'Gỏi Cuốn' => '🥟',
    'Nem' => '🍤',
    'Lẩu' => '🍲',
    'Trà sữa' => '🧋',
    'Sinh Tố' => '🍹',
    'Nước ép' => '🧃',
    'Trà' => '🍵',
    'Kem' => '🍦',
    'Sushi' => '🍣',
];

// Hàm lấy danh sách món theo loại và giới hạn
function getMenuItemsByType($conn, $type, $limit = 10) {
    // Kiểm tra $limit là số nguyên và an toàn trước khi đưa vào SQL
    $limit = (int)$limit;

    // Câu truy vấn với LIMIT được nối trực tiếp (đảm bảo $limit là số an toàn)
    $sql = "SELECT * FROM menu_items WHERE type = ? LIMIT $limit";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $type);  // Chỉ bind param type, limit nối trực tiếp
    $stmt->execute();
    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $items;
}


$foodItems = getMenuItemsByType($conn, 'food');
$drinkItems = getMenuItemsByType($conn, 'drink');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Tìm kiếm món ăn - DATASTORE FOOD</title>
    <link rel="stylesheet" href="../../assets/css/customer/timkiem.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Fallback khi ảnh bị lỗi, đổi sang ảnh mặc định
        function handleImageError(img) {
            img.onerror = null;
            img.src = '../../assets/images/default.jpg';
        }

        // Focus vào input tìm kiếm khi load trang
        window.onload = () => {
            const input = document.querySelector('input[name="search_term"]');
            if (input) input.focus();
        };
    </script>
</head>
<body>
<header>
    <h1>🍔 DATASTORE FOOD - Tìm kiếm món ăn</h1>
</header>

<main>
    <div class="back-to-home">
        <a href="home.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    </div>

    <!-- Form tìm kiếm -->
    <section class="search-section">
        <form action="timkiem.php" method="GET">
            <input
                type="text"
                name="search_term"
                placeholder="Tìm món ăn hoặc món nước..."
                value="<?= htmlspecialchars($_GET['search_term'] ?? '') ?>"
                required
            />
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
            <button type="submit">Tìm kiếm</button>
        </form>
    </section>


<!-- Gợi ý danh mục -->
<section class="categories">
    <form class="category-form" action="timkiem.php" method="GET">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
        <div class="category-container">
            <?php foreach ($categories as $category => $icon): ?>
                <button
                    type="submit"
                    name="search_term"
                    value="<?= htmlspecialchars($category) ?>"
                    class="category-btn"
                >
                    <?= $icon ?> <?= $category ?>
                </button>
            <?php endforeach; ?>
        </div>
    </form>
</section>


    <!-- Kết quả tìm kiếm -->
 <div class="scroll-container">
 <div class="collection-list"></div>
    <section class="search-results">
        <?php if (!empty($searchResults)): ?>
            <h2>Kết quả tìm kiếm:</h2>
            <div class="collection-list">
                <?php foreach ($searchResults as $item): ?>
                    <div class="collection-card">
                        <img
                            src="<?= getImagePath($item['type'], $item['image_path']) ?>"
                            onerror="handleImageError(this)"
                            alt="<?= htmlspecialchars($item['name']) ?>"
                            width="100"
                        />
                        <p><strong><?= htmlspecialchars($item['name']) ?></strong></p>
                        <p class="price"><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
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
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_GET['search_term'])): ?>
            <p>
                Không tìm thấy món ăn nào phù hợp với từ khóa
                "<strong><?= htmlspecialchars($_GET['search_term']) ?></strong>".
            </p>
        <?php endif; ?>
    </section>
 </div>
</div>

    <!-- Món ăn hôm nay -->
     <div class="scroll-container">
       <div class="collection-list">
    <section class="collections-wrapper">
        <section class="food-collection-section collections">
            <h2>Món ăn hôm nay</h2>
            <div class="collection-list">
                <?php if (empty($foodItems)): ?>
                    <p>Hiện tại không có món ăn nào.</p>
                <?php else: ?>
                    <?php foreach ($foodItems as $item): ?>
                        <div class="collection-card">
                            <img
                                src="<?= getImagePath('food', $item['image_path']) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                width="100"
                                onerror="handleImageError(this)"
                            />
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
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </section>
  </div>
</div>

    <!-- Món nước hôm nay -->
     <div class="scroll-container">
  <div class="collection-list">
    <section class="collections-wrapper">
        <section class="drink-collection-section collections">
            <h2>Món nước hôm nay</h2>
            <div class="collection-list">
                <?php if (empty($drinkItems)): ?>
                    <p>Hiện tại không có món nước nào.</p>
                <?php else: ?>
                    <?php foreach ($drinkItems as $item): ?>
                        <div class="collection-card">
                            <img
                                src="<?= getImagePath('drink', $item['image_path']) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                width="100"
                                onerror="handleImageError(this)"
                            />
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
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </section>
  </div>
</div>
</main>

<?php include '../../includes/footer_1.php'; ?>

</body>
</html>
