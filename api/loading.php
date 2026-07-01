<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use MemoNetwork\Core\Database;
use MemoNetwork\Core\Settings;

header('Content-Type: application/json; charset=utf-8');

$pdo = Database::connect();
$settings = Settings::all();
$news = $pdo->query('SELECT title, body, sort_order FROM loading_news WHERE is_active = 1 ORDER BY sort_order ASC, id DESC LIMIT 10')->fetchAll();

echo json_encode([
    'ok' => true,
    'version' => 'Alpha 26 Sprint 3',
    'loading' => [
        'title' => $settings['loading_title'] ?? ($settings['server_name'] ?? 'MemoNetwork'),
        'subtitle' => $settings['loading_subtitle'] ?? ($settings['server_subtitle'] ?? 'Industrial Sandbox'),
        'tip' => $settings['loading_tip'] ?? 'Welkom op MemoNetwork.',
        'background_url' => $settings['loading_background_url'] ?? '',
        'music_url' => $settings['loading_music_url'] ?? '',
        'volume' => (int)($settings['loading_volume'] ?? 35),
        'accent_color' => $settings['loading_accent_color'] ?? '#ff9100',
        'overlay_opacity' => (int)($settings['loading_overlay_opacity'] ?? 55),
    ],
    'news' => $news,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
