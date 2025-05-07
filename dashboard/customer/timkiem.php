<?php
// Kết nối cơ sở dữ liệu
include("../../includes/db.php");

// Biến lưu trữ kết quả tìm kiếm và món ăn gợi ý
$searchResults = [];
$suggestions = [];

// Kiểm tra nếu có từ khóa tìm kiếm trong URL
if (isset($_GET['search_term'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search_term']);

    // Truy vấn tìm kiếm món ăn
    $query = "SELECT * FROM menu WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);
    
    // Kiểm tra lỗi SQL
    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }

    // Lưu kết quả tìm kiếm
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    } else {
        $searchResults = []; // Không có kết quả, trả về mảng rỗng
    }
}

// Truy vấn món ăn phổ biến gợi ý
$suggestionQuery = "SELECT * FROM menu WHERE popularity > 50 LIMIT 10";
$suggestionResult = mysqli_query($conn, $suggestionQuery);

// Kiểm tra lỗi SQL
if (!$suggestionResult) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

// Lưu gợi ý món ăn
if (mysqli_num_rows($suggestionResult) > 0) {
    while ($row = mysqli_fetch_assoc($suggestionResult)) {
        $suggestions[] = $row;
    }
}

// Các nhóm món ăn gợi ý
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
    <style>
        /* Các kiểu CSS để thiết kế giao diện như trong mã của bạn */
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        /* Các kiểu CSS khác */
    </style>
</head>
<body>
    <header>
        <h1>🍔 DATASTORE FOOD - Tìm kiếm món ăn</h1>
    </header>

    <main>
        <section class="search-section">
            <form action="timkiem.php" method="GET">
                <input type="text" name="search_term" placeholder="Tìm món ăn hoặc món nước..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>" required>
                <button type="submit">Tìm kiếm</button>
            </form>
        </section>

        <section class="categories">
            <form class="category-form">
                <?php foreach ($categories as $category => $icon): ?>
                    <button type="submit" name="search_term" value="<?php echo $category; ?>" class="category-btn">
                        <span><?php echo $icon; ?></span> <?php echo $category; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        </section>

        <section class="search-results">
            <?php if (!empty($searchResults)): ?>
                <h2>Kết quả tìm kiếm:</h2>
                <ul>
                    <?php foreach ($searchResults as $item): ?>
                        <li>
                            <a href="menu.php?id=<?php echo $item['id']; ?>">
                                <img src="../../assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                <span><?php echo $item['name']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php elseif (isset($_GET['search_term'])): ?>
                <p>Không tìm thấy món ăn nào phù hợp với từ khóa tìm kiếm.</p>
            <?php endif; ?>
        </section>

        <section class="suggestions">
            <h2>Món ăn phổ biến:</h2>
            <ul>
                <?php foreach ($suggestions as $suggestion): ?>
                    <li>
                        <a href="menu.php?id=<?php echo $suggestion['id']; ?>">
                            <img src="../../assets/images/<?php echo $suggestion['image']; ?>" alt="<?php echo $suggestion['name']; ?>">
                            <span><?php echo $suggestion['name']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>IT_STARTUP TEAM - Khởi nghiệp cùng bạn!</p>
    </footer>
</body>
</html>
