<div class="mn-topbar">
    <div>
        <h1 class="mn-title"><?= htmlspecialchars($settings['server_name'] ?? 'MemoNetwork') ?></h1>
        <p class="mn-muted"><?= htmlspecialchars($settings['server_subtitle'] ?? 'Industrial Sandbox') ?> — welkom terug, <?= htmlspecialchars($user['username'] ?? 'admin') ?>.</p>
    </div>
    <div class="mn-muted"><?= htmlspecialchars($settings['server_version'] ?? 'Alpha 26.0-dev') ?></div>
</div>

<div class="mn-grid">
    <section class="mn-card mn-card-status">
        <h3>Server</h3>
        <div class="mn-value">Offline</div>
        <p class="mn-muted">Wacht op GMod API-koppeling.</p>
    </section>
    <section class="mn-card">
        <h3>Players</h3>
        <div class="mn-value">0</div>
        <p class="mn-muted">Live player sync komt in Sprint 3.</p>
    </section>
    <section class="mn-card">
        <h3>Builds</h3>
        <div class="mn-value">0</div>
        <p class="mn-muted">Build browser voorbereiding.</p>
    </section>
    <section class="mn-card">
        <h3>Alerts</h3>
        <div class="mn-value">0</div>
        <p class="mn-muted">Automation alerts komen later.</p>
    </section>
</div>

<div class="mn-two-col" style="margin-top:18px;">
    <section class="mn-card">
        <h3>Quick actions</h3>
        <div class="mn-quick-actions">
            <a class="mn-button" href="settings.php">Settings</a>
            <a class="mn-button" href="news.php">News Manager</a>
            <a class="mn-button" href="api/status.php">API status</a>
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
        <p class="mn-muted">Nog geen nieuws. Voeg je eerste bericht toe via News Manager.</p>
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
