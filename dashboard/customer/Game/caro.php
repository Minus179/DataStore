<?php
session_start();
include('../../../includes/db.php');


$user_id = $_SESSION['user_id'] ?? null;
$today = date('Y-m-d');
$can_play = true;

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM game_log WHERE user_id = ? AND play_date = ?");
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $can_play = false;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Caro 20x15 - Người vs Máy</title>
    <link rel="stylesheet" href="caro.css">
</head>
<body>
    <div class="caro-container">
        <h2>Game Caro 5x5: Người vs Máy</h2>
        <div id="board"></div>
        <p id="status">Lượt của bạn (X)</p>
        <button onclick="resetGame()">Chơi lại</button>
    </div>
    <script src="caro.js"></script>
</body>
</html>
