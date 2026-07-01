<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$saved = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare('UPDATE alerts SET is_resolved = 1, resolved_at = NOW() WHERE id = ?')->execute([$id]);
            $saved = true;
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$alerts = $pdo->query('SELECT * FROM alerts ORDER BY is_resolved ASC, id DESC LIMIT 200')->fetchAll();

View::render('alerts/index', [
    'title' => 'Alerts - MemoNetwork',
    'user' => Auth::user(),
    'alerts' => $alerts,
    'saved' => $saved,
    'error' => $error,
]);
