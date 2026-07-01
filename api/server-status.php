<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use MemoNetwork\Core\Database;
use MemoNetwork\Core\Env;

header('Content-Type: application/json; charset=utf-8');

function bearer_token(): string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(.*)$/i', $header, $m)) {
        return trim($m[1]);
    }
    return $_POST['api_token'] ?? $_GET['api_token'] ?? '';
}

$token = bearer_token();
$expected = (string)Env::get('API_TOKEN', '');

if ($expected !== '' && !hash_equals($expected, $token)) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}

$payload = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$serverKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $payload['server_key'] ?? 'main') ?: 'main';
$pdo = Database::connect();

$stmt = $pdo->prepare('INSERT INTO server_status
(server_key, server_name, is_online, map_name, players_online, max_players, entities, props, vehicles, wire_entities, server_fps, ram_mb, cpu_percent, uptime_seconds, health, last_seen)
VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
ON DUPLICATE KEY UPDATE
server_name = VALUES(server_name), is_online = 1, map_name = VALUES(map_name), players_online = VALUES(players_online), max_players = VALUES(max_players), entities = VALUES(entities), props = VALUES(props), vehicles = VALUES(vehicles), wire_entities = VALUES(wire_entities), server_fps = VALUES(server_fps), ram_mb = VALUES(ram_mb), cpu_percent = VALUES(cpu_percent), uptime_seconds = VALUES(uptime_seconds), health = VALUES(health), last_seen = NOW()');

$stmt->execute([
    $serverKey,
    $payload['server_name'] ?? 'MemoNetwork',
    $payload['map_name'] ?? null,
    (int)($payload['players_online'] ?? 0),
    (int)($payload['max_players'] ?? 0),
    (int)($payload['entities'] ?? 0),
    (int)($payload['props'] ?? 0),
    (int)($payload['vehicles'] ?? 0),
    (int)($payload['wire_entities'] ?? 0),
    isset($payload['server_fps']) ? (float)$payload['server_fps'] : null,
    isset($payload['ram_mb']) ? (int)$payload['ram_mb'] : null,
    isset($payload['cpu_percent']) ? (float)$payload['cpu_percent'] : null,
    isset($payload['uptime_seconds']) ? (int)$payload['uptime_seconds'] : null,
    $payload['health'] ?? 'online',
]);

if (isset($payload['players']) && is_array($payload['players'])) {
    $pdo->prepare('DELETE FROM server_players WHERE server_key = ?')->execute([$serverKey]);
    $playerStmt = $pdo->prepare('INSERT INTO server_players (server_key, steam_id, steam_id64, player_name, ping, team_name, connected_seconds) VALUES (?, ?, ?, ?, ?, ?, ?)');
    foreach ($payload['players'] as $player) {
        $playerStmt->execute([
            $serverKey,
            $player['steam_id'] ?? 'unknown',
            $player['steam_id64'] ?? null,
            $player['name'] ?? 'Unknown Player',
            isset($player['ping']) ? (int)$player['ping'] : null,
            $player['team'] ?? null,
            isset($player['connected_seconds']) ? (int)$player['connected_seconds'] : null,
        ]);
    }
}

echo json_encode(['ok' => true, 'server_key' => $serverKey]);
