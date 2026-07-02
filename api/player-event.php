<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use MemoNetwork\Core\Database;
use MemoNetwork\Core\Env;

header('Content-Type: application/json; charset=utf-8');

function bearer_token(): string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(.*)$/i', $header, $m)) return trim($m[1]);
    return $_POST['api_token'] ?? $_GET['api_token'] ?? '';
}

$expected = (string)Env::get('API_TOKEN', '');
if ($expected !== '' && !hash_equals($expected, bearer_token())) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}

$payload = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($payload)) $payload = $_POST;

$steamId = trim($payload['steam_id'] ?? '');
$name = trim($payload['player_name'] ?? $payload['name'] ?? 'Unknown Player');
$event = trim($payload['event'] ?? 'seen');
$serverKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $payload['server_key'] ?? 'main') ?: 'main';

if ($steamId === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'steam_id is required']);
    exit;
}

$pdo = Database::connect();
$pdo->exec("CREATE TABLE IF NOT EXISTS player_profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    steam_id VARCHAR(40) NOT NULL UNIQUE,
    steam_id64 VARCHAR(40) NULL,
    player_name VARCHAR(120) NOT NULL,
    avatar_url VARCHAR(255) NULL,
    first_seen TIMESTAMP NULL,
    last_seen TIMESTAMP NULL,
    total_playtime_seconds INT NOT NULL DEFAULT 0,
    notes TEXT NULL,
    warnings INT NOT NULL DEFAULT 0,
    bans INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$pdo->exec("CREATE TABLE IF NOT EXISTS player_events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    steam_id VARCHAR(40) NOT NULL,
    event_type VARCHAR(80) NOT NULL,
    message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$stmt = $pdo->prepare('INSERT INTO player_profiles (steam_id, steam_id64, player_name, avatar_url, first_seen, last_seen) VALUES (?, ?, ?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE player_name = VALUES(player_name), steam_id64 = VALUES(steam_id64), avatar_url = VALUES(avatar_url), last_seen = NOW()');
$stmt->execute([$steamId, $payload['steam_id64'] ?? null, $name, $payload['avatar_url'] ?? null]);

if ($event === 'disconnect' && isset($payload['session_seconds'])) {
    $pdo->prepare('UPDATE player_profiles SET total_playtime_seconds = total_playtime_seconds + ? WHERE steam_id = ?')
        ->execute([(int)$payload['session_seconds'], $steamId]);
}

$pdo->prepare('INSERT INTO player_events (server_key, steam_id, event_type, message) VALUES (?, ?, ?, ?)')
    ->execute([$serverKey, $steamId, $event, $payload['message'] ?? null]);

$pdo->prepare('INSERT INTO action_logs (server_key, action_type, actor, target, message) VALUES (?, ?, ?, ?, ?)')
    ->execute([$serverKey, 'player_' . $event, 'server', $name, $payload['message'] ?? 'Player event received.']);

echo json_encode(['ok' => true]);
