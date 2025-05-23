<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../../../login/login.php");
    exit();
}

include("../../../includes/db.php");
include("../../../includes/header.php");

$result = mysqli_query($conn, "SELECT * FROM games");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mini Game</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    margin: 0;
    padding: 40px 20px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2 {
    font-size: 2.8rem;
    color: #0f4c5c;
    text-align: center;
    margin-bottom: 40px;
    font-weight: 700;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.1);
}

.game-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 30px;
    width: 100%;
    max-width: 1200px;
}

.game-card {
    background: #ffffffcc;
    border-radius: 20px;
    box-shadow: 0 10px 20px rgba(15, 76, 92, 0.15);
    overflow: hidden;
    text-decoration: none;
    color: #1b262c;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    cursor: pointer;
    box-sizing: border-box;
}

.game-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 30px rgba(15, 76, 92, 0.25);
    background: #ffffffee;
}

.game-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 20px 20px 0 0;
    filter: drop-shadow(0 3px 6px rgba(0,0,0,0.1));
    transition: transform 0.3s ease;
}

.game-card:hover img {
    transform: scale(1.05);
}

.game-card h3 {
    font-size: 1.6rem;
    margin: 18px 16px 8px;
    font-weight: 700;
    letter-spacing: 0.03em;
}

.game-card p {
    flex-grow: 1;
    font-size: 1rem;
    color: #495057;
    margin: 0 16px 20px;
    line-height: 1.4;
    font-weight: 400;
}

/* Responsive tweaks */
@media (max-width: 600px) {
    body {
        padding: 30px 15px;
    }
    h2 {
        font-size: 2rem;
        margin-bottom: 30px;
    }
    .game-card h3 {
        font-size: 1.3rem;
    }
    .game-card p {
        font-size: 0.9rem;
    }
}

    </style>
</head>
<body>
    <h2>🎮 Danh sách Mini Game</h2>
    <div class="game-list">
        <?php while ($game = mysqli_fetch_assoc($result)) { ?>
           <div class="game-card" data-game-file="<?= htmlspecialchars($game['file_game']) ?>">
    <img src="../../../assets/images/games/<?= htmlspecialchars($game['avatar']) ?>" alt="Ảnh game">
    <h3><?= htmlspecialchars($game['ten_game']) ?></h3>
    <p><?= htmlspecialchars($game['mo_ta']) ?></p>   
     <div id="game-container" style="margin-top: 40px; min-height: 400px; border: 2px solid #388e85; border-radius: 12px; padding: 20px; background: #fff;">
    <p style="color: #388e85; font-weight: 600;">Chọn một game để chơi</p>
</div>
</div>
        <?php } ?>
    </div>*
    


</body>
<script>
    const gameCards = document.querySelectorAll('.game-card');
    const gameContainer = document.getElementById('game-container');

    gameCards.forEach(card => {
        card.addEventListener('click', () => {
            const gameFile = card.getAttribute('data-game-file');
            if (!gameFile) {
                gameContainer.innerHTML = '<p style="color:red;">Game chưa có file để tải.</p>';
                return;
            }
            // Load game bằng iframe
            gameContainer.innerHTML = `
                <iframe src="../../../games/${gameFile}" 
                        style="width: 100%; height: 400px; border: none; border-radius: 12px;"
                        allowfullscreen>
                </iframe>
            `;
        });
    });
</script>

</html>
