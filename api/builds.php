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

$expected = (string)Env::get('API_TOKEN', '');
$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($expected !== '' && !hash_equals($expected, bearer_token())) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
        exit;
    }

    $payload = json_decode(file_get_contents('php://input') ?: '{}', true);
    if (!is_array($payload)) {
        $payload = $_POST;
    }

    $stmt = $pdo->prepare('INSERT INTO builds (server_key, build_name, owner_name, owner_steam_id, map_name, props, vehicles, wire_entities, performance_score, preview_url, file_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $payload['server_key'] ?? 'main',
        $payload['build_name'] ?? 'Untitled Build',
        $payload['owner_name'] ?? null,
        $payload['owner_steam_id'] ?? null,
        $payload['map_name'] ?? null,
        (int)($payload['props'] ?? 0),
        (int)($payload['vehicles'] ?? 0),
        (int)($payload['wire_entities'] ?? 0),
        (int)($payload['performance_score'] ?? 100),
        $payload['preview_url'] ?? null,
        $payload['file_url'] ?? null,
    ]);

    echo json_encode(['ok' => true, 'id' => (int)$pdo->lastInsertId()]);
    exit;
}

$builds = $pdo->query('SELECT * FROM builds ORDER BY updated_at DESC LIMIT 100')->fetchAll();
echo json_encode(['ok' => true, 'builds' => $builds], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
