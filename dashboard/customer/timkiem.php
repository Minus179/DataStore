<?php
// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

// Biến lưu trữ kết quả tìm kiếm và món ăn gợi ý
$searchResults = [];
$suggestions = [];

// Kiểm tra nếu có từ khóa tìm kiếm trong URL
if (isset($_GET['search_term'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search_term']);

    // Truy vấn tìm kiếm món ăn từ bảng menu_items
    $query = "SELECT * FROM menu_items WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $searchResults[] = $row;
    }
}

// Truy vấn món ăn phổ biến gợi ý
$suggestionQuery = "SELECT * FROM menu_items WHERE popularity > 50 ORDER BY popularity DESC LIMIT 10";
$suggestionResult = mysqli_query($conn, $suggestionQuery);

if (!$suggestionResult) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($suggestionResult)) {
    $suggestions[] = $row;
}

$categories = [
    'Phở' => '🍲',
    'Bún' => '🍜',
    'Bánh Canh' => '🍲',
    'Cơm' => '🍚',
    'Món khai vị' => '🥢',
    'Món chính' => '🍛',
    'Món tráng miệng' => '🍮',
    'Nước giải khát' => '🥤',
    'Cà phê' => '☕'
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm món ăn - DATASTORE FOOD</title>
    <link rel="stylesheet" href="../../assets/css/customer/timkiem.css?v=<?=time()?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <h1>🍔 DATASTORE FOOD - Tìm kiếm món ăn</h1>
    </header>

    <main>
    <div class="back-to-home">
  <a href="home.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> 
  </a>
    </div>
        <!-- Form tìm kiếm -->
        <section class="search-section">
            <form action="timkiem.php" method="GET">
                <input type="text" name="search_term" placeholder="Tìm món ăn hoặc món nước..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>" required>
                <button type="submit">Tìm kiếm</button>
            </form>
        </section>

        <!-- Danh mục gợi ý -->
        <section class="categories">
            <form class="category-form">
                <?php foreach ($categories as $category => $icon): ?>
                    <button type="submit" name="search_term" value="<?php echo $category; ?>" class="category-btn">
                        <span><?php echo $icon; ?></span> <?php echo $category; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        </section>

        <!-- Kết quả tìm kiếm -->
<!-- Kết quả tìm kiếm -->
<section class="search-results">
    <?php if (!empty($searchResults)): ?>
        <h2>Kết quả tìm kiếm:</h2>
        <div class="collection-list">
            <?php foreach ($searchResults as $item): ?>
                <div class="collection-card">
                    <?php
                        $folder = ($item['type'] == 'food') ? "food" : "drink";
                        $fileName = $item['image_path'] ?? '';
                        $imagePath = "../../assets/images/{$folder}/{$fileName}";

                        if (!file_exists($imagePath) || empty($fileName)) {
                            $imagePath = "../../assets/images/default.jpg";
                        }
                    ?>
                    <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="100">
                    <p><?= htmlspecialchars($item['name']) ?></p>
                    <p><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn-add-cart">Thêm vào giỏ</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_GET['search_term'])): ?>
        <p>Không tìm thấy món ăn nào phù hợp. Vui lòng thử lại với từ khóa khác!</p>
    <?php endif; ?>
</section>


        <!-- Món phổ biến -->
        <section class="suggestions">
            <h2>Món ăn phổ biến:</h2>
            <ul>
                <?php foreach ($suggestions as $suggestion): ?>
                    <?php
                        $folder = ($suggestion['type'] == 'food') ? "food" : "drink";
                        $fileName = $suggestion['image_path'] ?? '';
                        $imagePath = "../../assets/images/{$folder}/{$fileName}";

                        if (!file_exists($imagePath) || empty($fileName)) {
                            $imagePath = "../../assets/images/default.jpg";
                        }
                    ?>
                    <li>
                        <a href="menu.php?id=<?php echo $suggestion['id']; ?>">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $suggestion['name']; ?>" width="100">
                            <span><?php echo $suggestion['name']; ?></span>
                            <span>Lượt mua: <?php echo $suggestion['popularity']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

    <footer>
        <p>IT_STARTUP TEAM - Khởi nghiệp cùng bạn!</p>
    </footer>
</body>
</html>
