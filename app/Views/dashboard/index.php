<div class="mn-topbar" data-live-dashboard>
    <div>
        <h1 class="mn-title"><?= htmlspecialchars($settings['server_name'] ?? 'MemoNetwork') ?></h1>
        <p class="mn-muted"><?= htmlspecialchars($settings['server_subtitle'] ?? 'Industrial Sandbox') ?> — welcome back, <?= htmlspecialchars($user['username'] ?? 'admin') ?>.</p>
    </div>
    <div class="mn-top-status">
        <span data-live-badge class="<?= !empty($status['is_online']) ? 'mn-status-badge online' : 'mn-status-badge offline' ?>"><?= !empty($status['is_online']) ? 'ONLINE' : 'OFFLINE' ?></span>
        <span class="mn-muted"><?= htmlspecialchars($settings['server_version'] ?? 'Alpha 26.0-dev') ?></span>
    </div>
</div>

<div class="mn-grid" data-live-dashboard>
    <section class="mn-card mn-card-status">
        <h3>Server Status</h3>
        <div class="mn-value" data-live="server_status"><?= !empty($status['is_online']) ? 'Online' : 'Offline' ?></div>
        <p class="mn-muted">Health: <span data-live="health"><?= htmlspecialchars($status['health'] ?? 'offline') ?></span></p>
    </section>
    <section class="mn-card">
        <h3>Players</h3>
        <div class="mn-value" data-live="players"><?= (int)($status['players_online'] ?? 0) ?> / <?= (int)($status['max_players'] ?? 0) ?></div>
        <p class="mn-muted">Live player count</p>
    </section>
    <section class="mn-card">
        <h3>Map</h3>
        <div class="mn-value small" data-live="map"><?= htmlspecialchars($status['map_name'] ?? '-') ?></div>
        <p class="mn-muted">Current map</p>
    </section>
    <section class="mn-card">
        <h3>Last Seen</h3>
        <div class="mn-value small" data-live="last_seen"><?= htmlspecialchars($status['last_seen'] ?? 'Never') ?></div>
        <p class="mn-muted">Auto-refresh every 5 seconds</p>
    </section>
</div>

<div class="mn-grid" style="margin-top:18px;" data-live-dashboard>
    <section class="mn-card"><h3>Entities</h3><div class="mn-value" data-live="entities"><?= (int)($status['entities'] ?? 0) ?></div></section>
    <section class="mn-card"><h3>Props</h3><div class="mn-value" data-live="props"><?= (int)($status['props'] ?? 0) ?></div></section>
    <section class="mn-card"><h3>Vehicles</h3><div class="mn-value" data-live="vehicles"><?= (int)($status['vehicles'] ?? 0) ?></div></section>
    <section class="mn-card"><h3>Wire</h3><div class="mn-value" data-live="wire"><?= (int)($status['wire_entities'] ?? 0) ?></div></section>
</div>

<div class="mn-grid" style="margin-top:18px;" data-live-dashboard>
    <section class="mn-card"><h3>Server FPS</h3><div class="mn-value" data-live="fps"><?= htmlspecialchars((string)($status['server_fps'] ?? '-')) ?></div></section>
    <section class="mn-card"><h3>RAM</h3><div class="mn-value small" data-live="ram"><?= !empty($status['ram_mb']) ? (int)$status['ram_mb'] . ' MB' : '-' ?></div></section>
    <section class="mn-card"><h3>CPU</h3><div class="mn-value small" data-live="cpu"><?= !empty($status['cpu_percent']) ? htmlspecialchars((string)$status['cpu_percent']) . '%' : '-' ?></div></section>
    <section class="mn-card"><h3>Uptime</h3><div class="mn-value small" data-live="uptime"><?= !empty($status['uptime_seconds']) ? (int)$status['uptime_seconds'] . 's' : '-' ?></div></section>
</div>

<div class="mn-two-col" style="margin-top:18px;">
    <section class="mn-card">
        <h3>Quick Actions</h3>
        <div class="mn-quick-actions">
            <a class="mn-button" href="players.php">Players</a>
            <a class="mn-button" href="settings.php">Settings</a>
            <a class="mn-button" href="news.php">News Manager</a>
            <a class="mn-button" href="loading.php">Loading Screen</a>
            <a class="mn-button" href="api/live-status.php">Live API</a>
        </div>
    </section>

    <section class="mn-card">
        <h3>Links</h3>
        <p><strong>Website:</strong> <span class="mn-muted"><?= htmlspecialchars($settings['website_url'] ?? '-') ?></span></p>
        <p><strong>Discord:</strong> <span class="mn-muted"><?= htmlspecialchars($settings['discord_url'] ?? '-') ?></span></p>
        <p><strong>Footer:</strong> <span class="mn-muted"><?= htmlspecialchars($settings['footer_text'] ?? '-') ?></span></p>
    </section>
</div>

<section class="mn-card" style="margin-top:18px;">
    <h3>Latest News</h3>
    <?php if (empty($news)): ?>
        <p class="mn-muted">No news yet. Create your first message in News Manager.</p>
    <?php endif; ?>
    <div class="mn-list">
        <?php foreach ($news as $item): ?>
            <div class="mn-list-item compact">
                <div>
                    <strong><?= htmlspecialchars($item['title']) ?></strong>
                    <p class="mn-muted"><?= nl2br(htmlspecialchars($item['body'] ?? '')) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
