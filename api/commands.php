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
if ($expected !== '' && !hash_equals($expected, bearer_token())) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}

$pdo = Database::connect();
$serverKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['server_key'] ?? $_POST['server_key'] ?? 'main') ?: 'main';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '{}', true);
    if (!is_array($payload)) {
        $payload = $_POST;
    }

    $id = (int)($payload['id'] ?? 0);
    $status = $payload['status'] ?? 'done';
    $result = $payload['result'] ?? '';

    if ($id > 0 && in_array($status, ['sent','done','failed'], true)) {
        $stmt = $pdo->prepare('UPDATE command_queue SET status = ?, result = ?, completed_at = IF(? IN ("done", "failed"), NOW(), completed_at) WHERE id = ? AND server_key = ?');
        $stmt->execute([$status, $result, $status, $id, $serverKey]);
        echo json_encode(['ok' => true]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid command acknowledgement']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM command_queue WHERE server_key = ? AND status = "pending" ORDER BY id ASC LIMIT 20');
$stmt->execute([$serverKey]);
$commands = $stmt->fetchAll();

if ($commands) {
    $ids = array_column($commands, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $update = $pdo->prepare("UPDATE command_queue SET status = 'sent', sent_at = NOW() WHERE id IN ($placeholders)");
    $update->execute($ids);
}

echo json_encode(['ok' => true, 'commands' => $commands], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
