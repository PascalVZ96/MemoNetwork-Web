<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Action Logs</h1>
        <p class="mn-muted">Track web panel actions, queued commands and server events.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 5</div>
</div>

<section class="mn-card mn-form">
    <h3>Filter</h3>
    <form method="get" action="logs.php" class="mn-inline-form">
        <input class="mn-input" name="type" value="<?= htmlspecialchars($type ?? '') ?>" placeholder="queue_command, delete_build...">
        <button class="mn-button" type="submit">Filter</button>
        <a class="mn-button" href="logs.php">Reset</a>
    </form>
</section>

<section class="mn-card" style="margin-top:18px;">
    <h3>Recent Logs</h3>
    <div class="mn-table-wrap">
        <table class="mn-table">
            <thead><tr><th>ID</th><th>Type</th><th>Actor</th><th>Target</th><th>Message</th><th>Created</th></tr></thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= (int)$log['id'] ?></td>
                    <td><?= htmlspecialchars($log['action_type']) ?></td>
                    <td><?= htmlspecialchars($log['actor'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($log['target'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($log['message'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
