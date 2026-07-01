<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use MemoNetwork\Core\Database;
use MemoNetwork\Core\Env;
use MemoNetwork\Core\Settings;

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
    $p = json_decode(file_get_contents('php://input') ?: '{}', true);
    if (!is_array($p)) $p = $_POST;

    $serverKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $p['server_key'] ?? 'main') ?: 'main';
    $players = (int)($p['players_online'] ?? 0);
    $entities = (int)($p['entities'] ?? 0);
    $props = (int)($p['props'] ?? 0);
    $fps = isset($p['server_fps']) ? (float)$p['server_fps'] : null;
    $ram = isset($p['ram_mb']) ? (int)$p['ram_mb'] : null;
    $cpu = isset($p['cpu_percent']) ? (float)$p['cpu_percent'] : null;

    $stmt = $pdo->prepare('INSERT INTO monitoring_history (server_key, players_online, entities, props, server_fps, ram_mb, cpu_percent) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$serverKey, $players, $entities, $props, $fps, $ram, $cpu]);

    $alerts = [];
    $cpuLimit = (float)Settings::get('alert_cpu_percent', '90');
    $ramLimit = (int)Settings::get('alert_ram_mb', '4096');
    $fpsLimit = (float)Settings::get('alert_min_fps', '20');

    $alertStmt = $pdo->prepare('INSERT INTO alerts (server_key, severity, title, message) VALUES (?, ?, ?, ?)');
    if ($cpu !== null && $cpu >= $cpuLimit) {
        $alertStmt->execute([$serverKey, 'warning', 'High CPU usage', 'CPU is at ' . $cpu . '%.']);
        $alerts[] = 'cpu';
    }
    if ($ram !== null && $ram >= $ramLimit) {
        $alertStmt->execute([$serverKey, 'warning', 'High RAM usage', 'RAM is at ' . $ram . ' MB.']);
        $alerts[] = 'ram';
    }
    if ($fps !== null && $fps <= $fpsLimit) {
        $alertStmt->execute([$serverKey, 'critical', 'Low server FPS', 'Server FPS is at ' . $fps . '.']);
        $alerts[] = 'fps';
    }

    echo json_encode(['ok' => true, 'alerts' => $alerts]);
    exit;
}

$history = $pdo->query('SELECT * FROM monitoring_history ORDER BY id DESC LIMIT 120')->fetchAll();
echo json_encode(['ok' => true, 'history' => $history], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
