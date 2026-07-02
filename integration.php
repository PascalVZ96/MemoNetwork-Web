<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Env;
use MemoNetwork\Core\View;

Auth::requireLogin();

$baseUrl = rtrim((string)Env::get('APP_URL', 'https://memocraft.nl/adminpanel'), '/');
$apiToken = (string)Env::get('API_TOKEN', '');

View::render('integration/index', [
    'title' => 'Core Integration - MemoNetwork',
    'user' => Auth::user(),
    'baseUrl' => $baseUrl,
    'apiTokenPreview' => $apiToken ? substr($apiToken, 0, 8) . '...' . substr($apiToken, -6) : 'not set',
]);
