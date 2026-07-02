<?php use MemoNetwork\Core\Url; ?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'MemoNetwork') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(Url::to('assets/css/app.css')) ?>">
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
                <a href="<?= Url::to('dashboard.php') ?>">Dashboard</a>
                <a href="<?= Url::to('players.php') ?>">Players</a>
                <a href="<?= Url::to('profiles.php') ?>">Player Profiles</a>
                <a href="<?= Url::to('commands.php') ?>">Command Center</a>
                <a href="<?= Url::to('console.php') ?>">Live Console</a>
                <a href="<?= Url::to('monitoring.php') ?>">Monitoring</a>
                <a href="<?= Url::to('alerts.php') ?>">Alerts</a>
                <a href="<?= Url::to('builds.php') ?>">Builds</a>
                <a href="<?= Url::to('logs.php') ?>">Logs</a>
                <a href="<?= Url::to('integration.php') ?>">Core Integration</a>
                <a href="<?= Url::to('loading.php') ?>">Loading Screen</a>
                <a href="<?= Url::to('news.php') ?>">News</a>
                <a href="<?= Url::to('settings.php') ?>">Settings</a>
                <a href="<?= Url::to('migrate.php') ?>">Database Update</a>
                <a href="<?= Url::to('logout.php') ?>">Logout</a>
            </nav>
        </aside>
        <main class="mn-main">
            <?= $content ?>
        </main>
    </div>
<?php endif; ?>
<?php foreach (($scripts ?? []) as $script): ?>
    <script src="<?= Url::to($script) ?>"></script>
<?php endforeach; ?>
</body>
</html>
