<?php

namespace MemoNetwork\Core;

final class Settings
{
    public static function all(): array
    {
        $stmt = Database::connect()->query('SELECT setting_key, setting_value FROM settings');
        $settings = [];
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public static function get(string $key, string $default = ''): string
    {
        $stmt = Database::connect()->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        return $value !== false ? (string)$value : $default;
    }

    public static function set(string $key, string $value): void
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute([$key, $value]);
    }
}
