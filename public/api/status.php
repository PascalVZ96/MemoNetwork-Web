<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'ok' => true,
    'app' => 'MemoNetwork Web',
    'version' => 'Alpha 26.0-dev',
    'server' => [
        'online' => false,
        'players' => 0,
        'entities' => 0,
        'alerts' => 0,
    ],
], JSON_PRETTY_PRINT);
