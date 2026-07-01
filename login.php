<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\View;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (Auth::attempt($username, $password)) {
        header('Location: dashboard.php');
        exit;
    }

    View::render('auth/login', ['title' => 'Login - MemoNetwork', 'authLayout' => true, 'error' => 'Ongeldige login.']);
    exit;
}

View::render('auth/login', ['title' => 'Login - MemoNetwork', 'authLayout' => true]);
