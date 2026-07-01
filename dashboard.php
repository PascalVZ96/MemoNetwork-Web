<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\Settings;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$news = $pdo->query('SELECT * FROM loading_news WHERE is_active = 1 ORDER BY sort_order ASC, id DESC LIMIT 5')->fetchAll();

View::render('dashboard/index', [
    'title' => 'Dashboard - MemoNetwork',
    'user' => Auth::user(),
    'settings' => Settings::all(),
    'news' => $news,
]);
