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

$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '{}', true);
    if (!is_array($payload)) $payload = $_POST;
    $lines = $payload['lines'] ?? [$payload];
    $stmt = $pdo->prepare('INSERT INTO console_logs (server_key, level, message) VALUES (?, ?, ?)');
    foreach ($lines as $line) {
        $stmt->execute([
            $line['server_key'] ?? $payload['server_key'] ?? 'main',
            $line['level'] ?? 'info',
            $line['message'] ?? (string)($line ?? ''),
        ]);
    }
    echo json_encode(['ok' => true]);
    exit;
}

$logs = $pdo->query('SELECT * FROM console_logs ORDER BY id DESC LIMIT 200')->fetchAll();
echo json_encode(['ok' => true, 'logs' => $logs], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
