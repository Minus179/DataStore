<?php
session_start();
include("../../includes/db.php");

$searchResults = [];
$suggestions = [];

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Hàm lấy đường dẫn ảnh theo loại món
function getImagePath($type, $fileName) {
    $folder = ($type === 'food') ? 'food' : 'drink';
    $path = "../../assets/images/{$folder}/{$fileName}";
    return (!file_exists($path) || empty($fileName)) ? "../../assets/images/default.jpg" : $path;
}

// Xử lý tìm kiếm
if (!empty($_GET['search_term'])) {
    // Kiểm tra CSRF token cho GET (mặc dù ít cần, nhưng chuẩn)
    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token không hợp lệ. Vui lòng tải lại trang.");
    }

    $searchTerm = trim($_GET['search_term']);
    $param = "%$searchTerm%";

    $stmt = mysqli_prepare($conn, "SELECT * FROM menu_items WHERE name LIKE ? OR description LIKE ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $param, $param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
}

// Gợi ý món phổ biến
$suggestionQuery = "SELECT * FROM menu_items WHERE popularity > 50 ORDER BY popularity DESC LIMIT 10";
$suggestionResult = mysqli_query($conn, $suggestionQuery);
if (!$suggestionResult) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($suggestionResult)) {
    $suggestions[] = $row;
}

// Danh sách danh mục
$categories = [
    'Phở' => '🍲',
    'Bún' => '🍜',
    'Bánh Canh' => '🍲',
    'Cơm' => '🍚',
    'Món chính' => '🍛',
    'Cà phê' => '☕'
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm món ăn - DATASTORE FOOD</title>
    <link rel="stylesheet" href="../../assets/css/customer/timkiem.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Fallback nếu ảnh lỗi
        function handleImageError(img) {
            img.onerror = null;
            img.src = '../../assets/images/default.jpg';
        }

        // Focus ô tìm kiếm khi load trang
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
                <input type="text" name="search_term" placeholder="Tìm món ăn hoặc món nước..." value="<?= htmlspecialchars($_GET['search_term'] ?? '') ?>" required>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit">Tìm kiếm</button>
            </form>
        </section>

        <!-- Gợi ý danh mục -->
        <section class="categories">
            <form class="category-form" action="timkiem.php" method="GET">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php foreach ($categories as $category => $icon): ?>
                    <button type="submit" name="search_term" value="<?= htmlspecialchars($category) ?>" class="category-btn">
                        <?= $icon ?> <?= $category ?>
                    </button>
                <?php endforeach; ?>
            </form>
        </section>

<!-- Kết quả tìm kiếm -->
<section class="search-results">
    <?php if (!empty($searchResults)): ?>
        <h2>Kết quả tìm kiếm:</h2>
        <div class="collection-list">
            <?php foreach ($searchResults as $item): ?>
                <div class="collection-card">
                    <img src="<?= getImagePath($item['type'], $item['image_path']) ?>" onerror="handleImageError(this)" alt="<?= htmlspecialchars($item['name']) ?>" width="100">
                    <p><strong><?= htmlspecialchars($item['name']) ?></strong></p>
                    <p class="price"><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
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
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_GET['search_term'])): ?>
        <p>Không tìm thấy món ăn nào phù hợp với từ khóa "<strong><?= htmlspecialchars($_GET['search_term']) ?></strong>".</p>
    <?php endif; ?>
</section>

        <!-- Món phổ biến -->
        <section class="suggestions">
            <h2>Món ăn phổ biến:</h2>
            <ul class="popular-list">
                <?php foreach ($suggestions as $suggestion): ?>
                    <li>
                        <a href="items_detail.php?id=<?= $suggestion['id'] ?>">
                            <img src="<?= getImagePath($suggestion['type'], $suggestion['image_path']) ?>" alt="<?= htmlspecialchars($suggestion['name']) ?>" width="100" onerror="handleImageError(this)">
                            <div class="popular-info">
                                <span><strong><?= htmlspecialchars($suggestion['name']) ?></strong></span><br>
                                <span>Lượt mua: <?= $suggestion['popularity'] ?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>🚀 IT_STARTUP TEAM - Khởi nghiệp cùng bạn!</p>
    </footer>
</body>
</html>
