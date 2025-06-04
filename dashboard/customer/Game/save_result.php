<?php
include '../../includes/db.php'; // sửa đường dẫn nếu khác

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $data['user_id'] ?? null;
$game = $data['game'] ?? 'unknown';
$result = $data['result'] ?? 'lose';
$score = $data['score'] ?? 0;

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "Missing user_id"]);
    exit;
}

// Kiểm tra đã chơi game này hôm nay chưa
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) FROM game_results WHERE user_id = ? AND game_name = ? AND DATE(played_at) = ?");
$stmt->bind_param("iss", $user_id, $game, $today);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    echo json_encode(["success" => false, "message" => "Bạn đã chơi game này hôm nay. Vui lòng quay lại vào ngày mai."]);
    exit;
}

// Chưa chơi, tiến hành lưu kết quả
$stmt = $conn->prepare("INSERT INTO game_results (user_id, game_name, result, score, played_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("issi", $user_id, $game, $result, $score);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true, "message" => "Lưu kết quả thành công!"]);
?>
