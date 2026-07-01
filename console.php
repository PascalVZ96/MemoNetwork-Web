<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$user = Auth::user();
$saved = false;
$error = null;
$filter = trim($_GET['q'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $command = trim($_POST['console_command'] ?? '');
        if ($command === '') {
            throw new RuntimeException('Console command cannot be empty.');
        }
        $stmt = $pdo->prepare('INSERT INTO command_queue (server_key, command_type, payload, created_by) VALUES (?, ?, ?, ?)');
        $stmt->execute(['main', 'console', $command, $user['username'] ?? 'admin']);
        $pdo->prepare('INSERT INTO action_logs (server_key, action_type, actor, target, message) VALUES (?, ?, ?, ?, ?)')
            ->execute(['main', 'console_command', $user['username'] ?? 'admin', 'server', $command]);
        $saved = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

if ($filter !== '') {
    $stmt = $pdo->prepare('SELECT * FROM console_logs WHERE message LIKE ? OR level LIKE ? ORDER BY id DESC LIMIT 300');
    $stmt->execute(['%' . $filter . '%', '%' . $filter . '%']);
    $logs = $stmt->fetchAll();
} else {
    $logs = $pdo->query('SELECT * FROM console_logs ORDER BY id DESC LIMIT 300')->fetchAll();
}

View::render('console/index', [
    'title' => 'Live Console - MemoNetwork',
    'user' => $user,
    'logs' => $logs,
    'filter' => $filter,
    'saved' => $saved,
    'error' => $error,
]);
