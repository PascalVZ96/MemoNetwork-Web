<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$user = Auth::user();
$saved = false;
$error = null;

$allowed = ['kick','ban','mute','unmute','gag','ungag','freeze','unfreeze','ignite','slay','heal','bring','goto','spectate','console'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $type = $_POST['command_type'] ?? '';
        if (!in_array($type, $allowed, true)) {
            throw new RuntimeException('Invalid command type.');
        }

        $targetSteamId = trim($_POST['target_steam_id'] ?? '');
        $targetName = trim($_POST['target_name'] ?? '');
        $payload = trim($_POST['payload'] ?? '');

        if ($type !== 'console' && $targetSteamId === '' && $targetName === '') {
            throw new RuntimeException('Target SteamID or target name is required.');
        }
        if ($type === 'console' && $payload === '') {
            throw new RuntimeException('Console command cannot be empty.');
        }

        $stmt = $pdo->prepare('INSERT INTO command_queue (server_key, command_type, target_steam_id, target_name, payload, created_by) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute(['main', $type, $targetSteamId ?: null, $targetName ?: null, $payload ?: null, $user['username'] ?? 'admin']);

        $log = $pdo->prepare('INSERT INTO action_logs (server_key, action_type, actor, target, message) VALUES (?, ?, ?, ?, ?)');
        $log->execute(['main', 'queue_command', $user['username'] ?? 'admin', $targetSteamId ?: $targetName ?: 'server', strtoupper($type) . ' command queued.']);
        $saved = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$commands = $pdo->query('SELECT * FROM command_queue ORDER BY id DESC LIMIT 80')->fetchAll();
$players = $pdo->query("SELECT * FROM server_players WHERE server_key = 'main' ORDER BY player_name ASC")->fetchAll();

View::render('commands/index', [
    'title' => 'Commands - MemoNetwork',
    'user' => $user,
    'commands' => $commands,
    'players' => $players,
    'saved' => $saved,
    'error' => $error,
]);
