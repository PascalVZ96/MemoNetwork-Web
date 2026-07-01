<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Settings</h1>
        <p class="mn-muted">Beheer de basisinformatie van MemoNetwork Web.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 2</div>
</div>

<?php if (!empty($saved)): ?>
    <div class="mn-success">Instellingen opgeslagen.</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="mn-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form class="mn-card mn-form" method="post" action="settings.php">
    <h3>Server Identity</h3>

    <label>Servernaam</label>
    <input class="mn-input" name="server_name" value="<?= htmlspecialchars($settings['server_name'] ?? 'MemoNetwork') ?>">

    <label>Subtitle</label>
    <input class="mn-input" name="server_subtitle" value="<?= htmlspecialchars($settings['server_subtitle'] ?? 'Industrial Sandbox') ?>">

    <label>Versie</label>
    <input class="mn-input" name="server_version" value="<?= htmlspecialchars($settings['server_version'] ?? 'Alpha 26.0-dev') ?>">

    <label>Website URL</label>
    <input class="mn-input" name="website_url" value="<?= htmlspecialchars($settings['website_url'] ?? 'https://memocraft.nl') ?>">

    <label>Discord URL</label>
    <input class="mn-input" name="discord_url" value="<?= htmlspecialchars($settings['discord_url'] ?? '') ?>">

    <label>Footer tekst</label>
    <input class="mn-input" name="footer_text" value="<?= htmlspecialchars($settings['footer_text'] ?? 'Welkom op MemoNetwork') ?>">

    <button class="mn-button" type="submit">Opslaan</button>
</form>
