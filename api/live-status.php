<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use MemoNetwork\Core\Database;

header('Content-Type: application/json; charset=utf-8');

$serverKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['server_key'] ?? 'main') ?: 'main';
$pdo = Database::connect();

$stmt = $pdo->prepare('SELECT *, TIMESTAMPDIFF(SECOND, last_seen, NOW()) AS seconds_since_seen FROM server_status WHERE server_key = ? LIMIT 1');
$stmt->execute([$serverKey]);
$status = $stmt->fetch() ?: [
    'server_key' => $serverKey,
    'server_name' => 'MemoNetwork',
    'is_online' => 0,
    'health' => 'offline',
    'players_online' => 0,
    'max_players' => 0,
    'map_name' => null,
    'entities' => 0,
    'props' => 0,
    'vehicles' => 0,
    'wire_entities' => 0,
    'server_fps' => null,
    'ram_mb' => null,
    'cpu_percent' => null,
    'uptime_seconds' => null,
    'last_seen' => null,
    'seconds_since_seen' => null,
];

if (!empty($status['seconds_since_seen']) && (int)$status['seconds_since_seen'] > 90) {
    $status['is_online'] = 0;
    $status['health'] = 'offline';
}

$playersStmt = $pdo->prepare('SELECT steam_id, steam_id64, player_name, ping, team_name, connected_seconds, updated_at FROM server_players WHERE server_key = ? ORDER BY player_name ASC');
$playersStmt->execute([$serverKey]);
$players = $playersStmt->fetchAll();

echo json_encode([
    'ok' => true,
    'status' => $status,
    'players' => $players,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
