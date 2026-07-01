<?php

$done = false;
$error = null;
$envWritten = false;

function mn_env_value(string $key, string $default = ''): string
{
    $path = __DIR__ . '/.env';
    if (!is_file($path)) {
        return $default;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        if (trim($k) === $key) {
            return trim($v, " \t\n\r\0\x0B\"'");
        }
    }

    return $default;
}

$defaults = [
    'app_url' => 'https://memocraft.nl/adminpanel',
    'db_host' => mn_env_value('DB_HOST', 'localhost'),
    'db_port' => mn_env_value('DB_PORT', '3306'),
    'db_name' => mn_env_value('DB_DATABASE', 'bm104579_memoweb'),
    'db_user' => mn_env_value('DB_USERNAME', 'bm104579_memoweb'),
    'db_pass' => '',
    'owner_user' => 'owner',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $appUrl = trim($_POST['app_url'] ?? $defaults['app_url']);
        $dbHost = trim($_POST['db_host'] ?? 'localhost');
        $dbPort = trim($_POST['db_port'] ?? '3306');
        $dbName = trim($_POST['db_name'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = $_POST['db_pass'] ?? '';
        $ownerUser = trim($_POST['owner_user'] ?? 'owner');
        $ownerPass = $_POST['owner_pass'] ?? '';

        if ($dbName === '' || $dbUser === '') {
            throw new RuntimeException('Database naam en gebruiker zijn verplicht.');
        }
        if (strlen($ownerPass) < 8) {
            throw new RuntimeException('Gebruik minimaal 8 tekens voor het owner wachtwoord.');
        }

        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $schema = file_get_contents(__DIR__ . '/database/schema.sql');
        if ($schema === false) {
            throw new RuntimeException('database/schema.sql niet gevonden.');
        }
        $pdo->exec($schema);

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users');
        $stmt->execute();
        $userCount = (int)$stmt->fetchColumn();

        if ($userCount === 0) {
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
            $stmt->execute([$ownerUser, password_hash($ownerPass, PASSWORD_DEFAULT), 'owner']);
        }

        $appKey = bin2hex(random_bytes(24));
        $apiToken = bin2hex(random_bytes(32));
        $env = "APP_NAME=MemoNetwork\n" .
            "APP_ENV=production\n" .
            "APP_URL={$appUrl}\n" .
            "APP_KEY={$appKey}\n\n" .
            "DB_HOST={$dbHost}\n" .
            "DB_PORT={$dbPort}\n" .
            "DB_DATABASE={$dbName}\n" .
            "DB_USERNAME={$dbUser}\n" .
            "DB_PASSWORD={$dbPass}\n\n" .
            "API_TOKEN={$apiToken}\n";

        $envPath = __DIR__ . '/.env';
        $envWritten = @file_put_contents($envPath, $env) !== false;
        if (!$envWritten) {
            throw new RuntimeException('Database is gelukt, maar .env kon niet worden geschreven. Geef de map adminpanel schrijfrechten of maak .env handmatig aan.');
        }

        $done = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\View;

View::render('install/index', [
    'title' => 'Install - MemoNetwork',
    'authLayout' => true,
    'done' => $done,
    'error' => $error,
    'envWritten' => $envWritten,
    'defaults' => $defaults,
]);
