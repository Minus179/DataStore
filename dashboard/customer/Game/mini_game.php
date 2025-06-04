<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Game Vui Nhộn</title>
    <link rel="stylesheet" href="mini_game.css">
</head>
<body>
    <div class="container">
        <h2>Danh sách Game Vui Nhộn</h2><!-- Nút trở về trang chủ -->
        <div style="margin-bottom: 20px;">
            <a href="../home.php">
                <button>🏠 Trở về Trang chủ</button>
            </a>
        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">Tất cả</button>
            <button class="filter-btn" data-filter="tritue">Trí tuệ</button>
            <button class="filter-btn" data-filter="duaxe">Đua xe</button>
            <button class="filter-btn" data-filter="dovui">Đố vui</button>
            <button class="filter-btn" data-filter="mario">Mario-style</button>
        </div>

        <div class="game-list">
            <div class="game-card" data-category="tritue">
                <img src="../../../assets/images/game/caro.jpg" alt="Caro 20x15">
                <h4>Caro 5x5</h4>
                <p>Chơi caro phiên bản 5x5, chọn hàng khớp 5 ô liên tiếp.</p>
                <a href="caro.php"><button>Chơi</button></a>
            </div>

            <div class="game-card" data-category="tritue">
                <img src="../../../assets/images/game/trieu_phu.jpg" alt="Ai là triệu phú">
                <h4>Ai là triệu phú</h4>
                <p>Thử tài trí tuệ với các câu hỏi thú vị!</p>
                <a href="ailatrieuphu.html"><button>Chơi</button></a>
            </div>

            <div class="game-card" data-category="duaxe">
                <img src="../../../assets/images/game/dua_xe.jpg" alt="Đua xe">
                <h4>Đua xe</h4>
                <p>Lái xe mô chướng ngại vật để đạt điểm cao.</p>
                <a href="racing/index.html"><button>Chơi</button></a>
            </div>

            <div class="game-card" data-category="dovui">
                <img src="../../../assets/images/game/do_vui.jpg" alt="Game đố vui">
                <h4>Game đố vui</h4>
                <p>Câu đố hài hước, dí dỏm, khêu gợi tư duy!</p>
                <a href="funnyquiz/index.html"><button>Chơi</button></a>
            </div>

            <div class="game-card" data-category="mario">
                <img src="../../../assets/images/game/mario_quaivat.jpg" alt="Game Mario">
                <h4>Game Mario-style</h4>
                <p>Đi cứu công chúa như phong cách Mario.</p>
                <a href="mario/index.html"><button>Chơi</button></a>
            </div>
        </div>
    </div>

    <script>
        const filterButtons = document.querySelectorAll('.filter-btn');
        const gameCards = document.querySelectorAll('.game-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                document.querySelector('.filter-btn.active').classList.remove('active');
                button.classList.add('active');

                const filter = button.dataset.filter;
                gameCards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
