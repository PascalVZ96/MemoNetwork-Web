<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'MemoNetwork') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<?php if (!empty($authLayout)): ?>
    <?= $content ?>
<?php else: ?>
    <div class="mn-shell">
        <aside class="mn-sidebar">
            <div class="mn-logo">Memo<span>Network</span></div>
            <p class="mn-muted">Control Panel Alpha 26</p>
            <nav class="mn-nav">
                <a class="active" href="/dashboard.php">Dashboard</a>
                <a href="#">Players</a>
                <a href="#">Builds</a>
                <a href="#">Loading Screen</a>
                <a href="#">News</a>
                <a href="#">Monitoring</a>
                <a href="/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="mn-main">
            <?= $content ?>
        </main>
    </div>
<?php endif; ?>
</body>
</html>
