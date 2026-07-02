<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

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

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT * FROM player_profiles WHERE player_name LIKE ? OR steam_id LIKE ? OR steam_id64 LIKE ? ORDER BY last_seen DESC LIMIT 100');
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like, $like]);
    $profiles = $stmt->fetchAll();
} else {
    $profiles = $pdo->query('SELECT * FROM player_profiles ORDER BY last_seen DESC LIMIT 100')->fetchAll();
}

View::render('profiles/index', [
    'title' => 'Player Profiles - MemoNetwork',
    'user' => Auth::user(),
    'profiles' => $profiles,
    'search' => $search,
]);
