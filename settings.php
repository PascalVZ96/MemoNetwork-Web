<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Settings;
use MemoNetwork\Core\View;

Auth::requireLogin();

$saved = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach (['server_name', 'server_subtitle', 'server_version', 'website_url', 'discord_url', 'footer_text'] as $key) {
            Settings::set($key, trim($_POST[$key] ?? ''));
        }
        $saved = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

View::render('settings/index', [
    'title' => 'Settings - MemoNetwork',
    'user' => Auth::user(),
    'settings' => Settings::all(),
    'saved' => $saved,
    'error' => $error,
]);
