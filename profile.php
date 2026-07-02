<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$saved = false;
$error = null;
$steamId = trim($_GET['steam_id'] ?? $_POST['steam_id'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($steamId === '') throw new RuntimeException('SteamID missing.');
        $notes = trim($_POST['notes'] ?? '');
        $warnings = (int)($_POST['warnings'] ?? 0);
        $pdo->prepare('UPDATE player_profiles SET notes = ?, warnings = ? WHERE steam_id = ?')->execute([$notes, $warnings, $steamId]);
        $saved = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$stmt = $pdo->prepare('SELECT * FROM player_profiles WHERE steam_id = ? LIMIT 1');
$stmt->execute([$steamId]);
$profile = $stmt->fetch();

$eventsStmt = $pdo->prepare('SELECT * FROM player_events WHERE steam_id = ? ORDER BY id DESC LIMIT 100');
$eventsStmt->execute([$steamId]);
$events = $eventsStmt->fetchAll();

View::render('profiles/show', [
    'title' => 'Player Profile - MemoNetwork',
    'user' => Auth::user(),
    'profile' => $profile,
    'events' => $events,
    'saved' => $saved,
    'error' => $error,
]);
