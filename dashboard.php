<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\View;

Auth::requireLogin();

View::render('dashboard/index', [
    'title' => 'Dashboard - MemoNetwork',
    'user' => Auth::user(),
]);
