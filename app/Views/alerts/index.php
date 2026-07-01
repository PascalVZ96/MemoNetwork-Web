<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Alerts</h1>
        <p class="mn-muted">Warnings and critical events detected from server metrics.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 6</div>
</div>

<?php if (!empty($saved)): ?><div class="mn-success">Alert resolved.</div><?php endif; ?>
<?php if (!empty($error)): ?><div class="mn-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<section class="mn-card">
    <h3>Alert List</h3>
    <?php if (empty($alerts)): ?><p class="mn-muted">No alerts yet.</p><?php endif; ?>
    <div class="mn-list">
        <?php foreach ($alerts as $alert): ?>
            <div class="mn-list-item">
                <div>
                    <strong><?= htmlspecialchars($alert['title']) ?></strong>
                    <p class="mn-muted"><?= htmlspecialchars($alert['message'] ?? '') ?></p>
                    <small class="mn-muted"><?= htmlspecialchars($alert['severity']) ?> | <?= htmlspecialchars($alert['created_at']) ?> | <?= !empty($alert['is_resolved']) ? 'Resolved' : 'Open' ?></small>
                </div>
                <?php if (empty($alert['is_resolved'])): ?>
                    <form method="post" action="alerts.php">
                        <input type="hidden" name="id" value="<?= (int)$alert['id'] ?>">
                        <button class="mn-button" type="submit">Resolve</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
