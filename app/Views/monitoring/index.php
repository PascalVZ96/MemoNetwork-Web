<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Monitoring</h1>
        <p class="mn-muted">Historical server metrics and health overview.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 6</div>
</div>

<div class="mn-grid">
    <section class="mn-card"><h3>FPS</h3><div class="mn-value"><?= htmlspecialchars((string)($status['server_fps'] ?? '-')) ?></div></section>
    <section class="mn-card"><h3>RAM</h3><div class="mn-value small"><?= !empty($status['ram_mb']) ? (int)$status['ram_mb'] . ' MB' : '-' ?></div></section>
    <section class="mn-card"><h3>CPU</h3><div class="mn-value small"><?= !empty($status['cpu_percent']) ? htmlspecialchars((string)$status['cpu_percent']) . '%' : '-' ?></div></section>
    <section class="mn-card"><h3>Players</h3><div class="mn-value"><?= (int)($status['players_online'] ?? 0) ?></div></section>
</div>

<section class="mn-card" style="margin-top:18px;">
    <h3>Metric Timeline</h3>
    <?php if (empty($history)): ?><p class="mn-muted">No monitoring history received yet.</p><?php endif; ?>
    <div class="mn-table-wrap">
        <table class="mn-table">
            <thead><tr><th>Time</th><th>Players</th><th>FPS</th><th>RAM</th><th>CPU</th><th>Props</th><th>Entities</th></tr></thead>
            <tbody>
            <?php foreach ($history as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td><?= (int)$row['players_online'] ?></td>
                    <td><?= htmlspecialchars((string)($row['server_fps'] ?? '-')) ?></td>
                    <td><?= htmlspecialchars((string)($row['ram_mb'] ?? '-')) ?></td>
                    <td><?= htmlspecialchars((string)($row['cpu_percent'] ?? '-')) ?></td>
                    <td><?= (int)$row['props'] ?></td>
                    <td><?= (int)$row['entities'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
