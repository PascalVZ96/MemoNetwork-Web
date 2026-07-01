<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;
use MemoNetwork\Core\Database;
use MemoNetwork\Core\View;

Auth::requireLogin();

$pdo = Database::connect();
$saved = false;
$error = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? 'create';

        if ($action === 'delete') {
            $stmt = $pdo->prepare('DELETE FROM loading_news WHERE id = ?');
            $stmt->execute([(int)($_POST['id'] ?? 0)]);
            $saved = true;
        } elseif ($action === 'toggle') {
            $stmt = $pdo->prepare('UPDATE loading_news SET is_active = 1 - is_active WHERE id = ?');
            $stmt->execute([(int)($_POST['id'] ?? 0)]);
            $saved = true;
        } else {
            $title = trim($_POST['title'] ?? '');
            $body = trim($_POST['body'] ?? '');
            $sort = (int)($_POST['sort_order'] ?? 0);
            if ($title === '') {
                throw new RuntimeException('Titel is verplicht.');
            }
            $stmt = $pdo->prepare('INSERT INTO loading_news (title, body, sort_order, is_active) VALUES (?, ?, ?, 1)');
            $stmt->execute([$title, $body, $sort]);
            $saved = true;
        }
    }

    $news = $pdo->query('SELECT * FROM loading_news ORDER BY sort_order ASC, id DESC')->fetchAll();
} catch (Throwable $e) {
    $error = $e->getMessage();
    $news = [];
}

View::render('news/index', [
    'title' => 'News - MemoNetwork',
    'user' => Auth::user(),
    'news' => $news,
    'saved' => $saved,
    'error' => $error,
]);
