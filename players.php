<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$serverKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['server_key'] ?? 'main') ?: 'main';
$pdo = Database::connect();

$statusStmt = $pdo->prepare('SELECT *, TIMESTAMPDIFF(SECOND, last_seen, NOW()) AS seconds_since_seen FROM server_status WHERE server_key = ? LIMIT 1');
$statusStmt->execute([$serverKey]);
$status = $statusStmt->fetch();

$playersStmt = $pdo->prepare('SELECT * FROM server_players WHERE server_key = ? ORDER BY player_name ASC');
$playersStmt->execute([$serverKey]);
$players = $playersStmt->fetchAll();

View::render('players/index', [
    'title' => 'Players - MemoNetwork',
    'user' => Auth::user(),
    'status' => $status,
    'players' => $players,
]);
