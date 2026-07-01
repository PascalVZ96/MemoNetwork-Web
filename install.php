<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

$done = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Database::connect();
        $schema = file_get_contents(__DIR__ . '/database/schema.sql');
        $pdo->exec($schema);

        $username = trim($_POST['username'] ?? 'owner');
        $password = $_POST['password'] ?? '';
        if (strlen($password) < 8) {
            throw new RuntimeException('Gebruik minimaal 8 tekens voor het wachtwoord.');
        }

        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), 'owner']);

        $done = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

View::render('install/index', [
    'title' => 'Install - MemoNetwork',
    'authLayout' => true,
    'done' => $done,
    'error' => $error,
]);
