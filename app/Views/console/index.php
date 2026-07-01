<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Live Console</h1>
        <p class="mn-muted">View server console output and queue console commands.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 6</div>
</div>

<?php if (!empty($saved)): ?><div class="mn-success">Console command queued.</div><?php endif; ?>
<?php if (!empty($error)): ?><div class="mn-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="mn-two-col">
    <section class="mn-card mn-form">
        <h3>Send Console Command</h3>
        <form method="post" action="console.php">
            <label>Command</label>
            <input class="mn-input" name="console_command" placeholder="say Hello from the web panel" required>
            <button class="mn-button" type="submit">Queue Command</button>
        </form>
    </section>
    <section class="mn-card mn-form">
        <h3>Filter Logs</h3>
        <form method="get" action="console.php" class="mn-inline-form">
            <input class="mn-input" name="q" value="<?= htmlspecialchars($filter ?? '') ?>" placeholder="error, warning, player name...">
            <button class="mn-button" type="submit">Filter</button>
            <a class="mn-button" href="console.php">Reset</a>
        </form>
        <p class="mn-muted">MemoNetwork Core can push console output through <code>api/console.php</code>.</p>
    </section>
</div>

<section class="mn-card" style="margin-top:18px;">
    <h3>Console Output</h3>
    <div class="mn-console-box">
        <?php if (empty($logs)): ?><div class="mn-console-line muted">No console logs received yet.</div><?php endif; ?>
        <?php foreach ($logs as $log): ?>
            <div class="mn-console-line level-<?= htmlspecialchars($log['level']) ?>">
                <span>[<?= htmlspecialchars($log['created_at']) ?>]</span>
                <strong><?= htmlspecialchars(strtoupper($log['level'])) ?></strong>
                <?= htmlspecialchars($log['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
