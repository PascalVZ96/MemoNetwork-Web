<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$history = $pdo->query('SELECT * FROM monitoring_history ORDER BY id DESC LIMIT 120')->fetchAll();
$status = $pdo->query("SELECT * FROM server_status WHERE server_key = 'main' LIMIT 1")->fetch();

View::render('monitoring/index', [
    'title' => 'Monitoring - MemoNetwork',
    'user' => Auth::user(),
    'history' => array_reverse($history),
    'status' => $status ?: [],
]);
