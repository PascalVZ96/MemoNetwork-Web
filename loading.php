<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Settings;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$saved = false;
$error = null;
$uploadDir = __DIR__ . '/uploads/loading';

if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ([
            'loading_title', 'loading_subtitle', 'loading_tip', 'loading_background_url',
            'loading_music_url', 'loading_accent_color', 'loading_overlay_opacity', 'loading_volume'
        ] as $key) {
            Settings::set($key, trim($_POST[$key] ?? ''));
        }

        if (!empty($_FILES['background_file']['name']) && is_uploaded_file($_FILES['background_file']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['background_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'webm'], true)) {
                throw new RuntimeException('Achtergrondtype niet toegestaan. Gebruik jpg, png, webp, gif, mp4 of webm.');
            }
            $name = 'background_' . time() . '.' . $ext;
            if (!move_uploaded_file($_FILES['background_file']['tmp_name'], $uploadDir . '/' . $name)) {
                throw new RuntimeException('Achtergrond uploaden mislukt. Controleer maprechten van uploads/loading.');
            }
            Settings::set('loading_background_url', 'uploads/loading/' . $name);
        }

        if (!empty($_FILES['music_file']['name']) && is_uploaded_file($_FILES['music_file']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['music_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['mp3', 'ogg', 'wav'], true)) {
                throw new RuntimeException('Muziektype niet toegestaan. Gebruik mp3, ogg of wav.');
            }
            $name = 'music_' . time() . '.' . $ext;
            if (!move_uploaded_file($_FILES['music_file']['tmp_name'], $uploadDir . '/' . $name)) {
                throw new RuntimeException('Muziek uploaden mislukt. Controleer maprechten van uploads/loading.');
            }
            Settings::set('loading_music_url', 'uploads/loading/' . $name);
        }

        $saved = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$pdo = Database::connect();
$news = $pdo->query('SELECT * FROM loading_news WHERE is_active = 1 ORDER BY sort_order ASC, id DESC LIMIT 6')->fetchAll();

View::render('loading/index', [
    'title' => 'Loading Screen - MemoNetwork',
    'user' => Auth::user(),
    'settings' => Settings::all(),
    'news' => $news,
    'saved' => $saved,
    'error' => $error,
]);
