<?php

declare(strict_types=1);

use MemoNetwork\Core\Env;

session_start();

define('MN_BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class): void {
    $prefix = 'MemoNetwork\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = MN_BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

Env::load(MN_BASE_PATH . '/.env');
