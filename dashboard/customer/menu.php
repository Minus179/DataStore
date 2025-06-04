<?php
// Kết nối CSDL nếu cần (tuỳ dự án)
// include("includes/db.php");
include("includes/header.php"); // Đảm bảo có file này để giữ giao diện nhất quán
?>

<div class="container">
    <h2 class="title">Danh mục</h2>
    
    <!-- Sidebar tĩnh hoặc sẽ phát triển sau -->
    <div class="menu-content" style="display: flex;">
        <!-- Sidebar trái -->
        <div class="sidebar" style="width: 200px; margin-right: 20px;">
            <ul style="list-style: none; padding: 0;">
                <li>🔥 Phổ biến</li>
                <li>🍞 Bánh truyền thống</li>
                <li>🍲 Lẩu & Nướng</li>
                <li>🥗 Đồ ăn dinh dưỡng</li>
                <li>🥤 Đồ uống không cồn</li>
                <li>🍚 Cơm</li>
                <li>🍢 Đồ ăn vặt</li>
                <li>🍰 Tráng miệng</li>
                <li>🧁 Bánh Âu Á</li>
                <li>🥬 Đồ chay</li>
                <li>🍜 Bún/Phở/Mỳ</li>
            </ul>
        </div>

        <!-- Nội dung danh mục -->
        <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 20px;">
            <?php
            $categories = [
                ['name' => 'Cơm', 'img' => 'assets/categories/com.png'],
                ['name' => 'Trà sữa', 'img' => 'assets/categories/trasua.png'],
                ['name' => 'Đồ ăn vặt', 'img' => 'assets/categories/doanvat.png'],
                ['name' => 'Gà', 'img' => 'assets/categories/ga.png'],
                ['name' => 'Cà phê', 'img' => 'assets/categories/caphe.png'],
                ['name' => 'Tráng miệng', 'img' => 'assets/categories/trangmieng.png'],
                ['name' => 'Bún', 'img' => 'assets/categories/bun.png'],
                ['name' => 'Trà', 'img' => 'assets/categories/tra.png'],
                ['name' => 'Bánh Âu Á', 'img' => 'assets/categories/banhaua.png'],
                ['name' => 'Bún/Phở/Mỳ', 'img' => 'assets/categories/bunphomien.png'],
                ['name' => 'Nước ép & Sinh tố', 'img' => 'assets/categories/nuocep.png'],
                ['name' => 'Bánh tráng', 'img' => 'assets/categories/banhtrang.png'],
                ['name' => 'Cháo/Súp', 'img' => 'assets/categories/chao.png'],
                ['name' => 'Đồ ăn nhanh', 'img' => 'assets/categories/doannhanh.png'],
                ['name' => 'Hải sản', 'img' => 'assets/categories/haisan.png'],
                ['name' => 'Bánh mì', 'img' => 'assets/categories/banhmi.png'],
                ['name' => 'Pasta', 'img' => 'assets/categories/pasta.png'],
                ['name' => 'Heo', 'img' => 'assets/categories/heo.png'],
                ['name' => 'Lẩu', 'img' => 'assets/categories/lau.png'],
                ['name' => 'Gà rán', 'img' => 'assets/categories/garan.png'],
                ['name' => 'Bò', 'img' => 'assets/categories/bo.png']
            ];

            foreach ($categories as $cat) {
                echo '<div class="category-item" style="text-align: center;">
                        <img src="'.$cat['img'].'" alt="'.$cat['name'].'" style="width: 80px; height: 80px; object-fit: cover;">
                        <p>'.$cat['name'].'</p>
                    </div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
