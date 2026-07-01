<?php

namespace MemoNetwork\Core;

final class Url
{
    public static function basePath(): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = str_replace('\\', '/', dirname($script));
        if ($dir === '/' || $dir === '.' || $dir === '\\') {
            return '';
        }
        return rtrim($dir, '/');
    }

    public static function to(string $path = ''): string
    {
        $path = ltrim($path, '/');
        $base = self::basePath();
        return $base . ($path !== '' ? '/' . $path : '');
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . self::to($path));
        exit;
    }
}
