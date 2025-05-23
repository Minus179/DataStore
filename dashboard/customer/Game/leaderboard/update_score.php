<?php
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['name'], $data['score'], $data['game'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu dữ liệu']);
    exit;
}

$file = 'scores.json';
$scores = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$data['time'] = date('Y-m-d H:i:s');
$scores[] = $data;

file_put_contents($file, json_encode($scores, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
?>
