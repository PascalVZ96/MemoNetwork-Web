<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$type = trim($_GET['type'] ?? '');

if ($type !== '') {
    $stmt = $pdo->prepare('SELECT * FROM action_logs WHERE action_type LIKE ? ORDER BY id DESC LIMIT 200');
    $stmt->execute(['%' . $type . '%']);
    $logs = $stmt->fetchAll();
} else {
    $logs = $pdo->query('SELECT * FROM action_logs ORDER BY id DESC LIMIT 200')->fetchAll();
}

View::render('logs/index', [
    'title' => 'Logs - MemoNetwork',
    'user' => Auth::user(),
    'logs' => $logs,
    'type' => $type,
]);
