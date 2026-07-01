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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        $id = (int)($_POST['id'] ?? 0);

        if ($action === 'delete' && $id > 0) {
            $pdo->prepare('DELETE FROM builds WHERE id = ?')->execute([$id]);
            $pdo->prepare('INSERT INTO action_logs (server_key, action_type, actor, target, message) VALUES (?, ?, ?, ?, ?)')
                ->execute(['main', 'delete_build', $user['username'] ?? 'admin', 'build #' . $id, 'Build deleted from web panel.']);
            $saved = true;
        } elseif ($action === 'spawn' && $id > 0) {
            $build = $pdo->prepare('SELECT * FROM builds WHERE id = ? LIMIT 1');
            $build->execute([$id]);
            $data = $build->fetch();
            if (!$data) {
                throw new RuntimeException('Build not found.');
            }
            $payload = json_encode(['build_id' => $id, 'build_name' => $data['build_name'], 'file_url' => $data['file_url']], JSON_UNESCAPED_SLASHES);
            $pdo->prepare('INSERT INTO command_queue (server_key, command_type, payload, created_by) VALUES (?, ?, ?, ?)')
                ->execute(['main', 'spawn_build', $payload, $user['username'] ?? 'admin']);
            $saved = true;
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT * FROM builds WHERE build_name LIKE ? OR owner_name LIKE ? OR owner_steam_id LIKE ? ORDER BY updated_at DESC LIMIT 100');
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like, $like]);
    $builds = $stmt->fetchAll();
} else {
    $builds = $pdo->query('SELECT * FROM builds ORDER BY updated_at DESC LIMIT 100')->fetchAll();
}

View::render('builds/index', [
    'title' => 'Build Browser - MemoNetwork',
    'user' => $user,
    'builds' => $builds,
    'search' => $search,
    'saved' => $saved,
    'error' => $error,
]);
