<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$done = false;
$error = null;

try {
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    if ($schema === false) {
        throw new RuntimeException('database/schema.sql not found.');
    }
    Database::connect()->exec($schema);
    $done = true;
} catch (Throwable $e) {
    $error = $e->getMessage();
}

View::render('migrate/index', [
    'title' => 'Database Update - MemoNetwork',
    'user' => Auth::user(),
    'done' => $done,
    'error' => $error,
]);
