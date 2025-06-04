<?php
header('Content-Type: application/json');
$gameFilter = $_GET['game'] ?? null;

$scores = file_exists('scores.json') ? json_decode(file_get_contents('scores.json'), true) : [];

if ($gameFilter) {
    $scores = array_filter($scores, fn($s) => $s['game'] === $gameFilter);
}

usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

echo json_encode(array_slice($scores, 0, 10));
?>
