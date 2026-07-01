<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Loading Screen CMS</h1>
        <p class="mn-muted">Beheer tekst, achtergrond, muziek en preview van het loading screen.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 3</div>
</div>

<?php if (!empty($saved)): ?>
    <div class="mn-success">Loading screen instellingen opgeslagen.</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="mn-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="mn-two-col mn-loading-layout">
    <form class="mn-card mn-form" method="post" action="loading.php" enctype="multipart/form-data">
        <h3>Tekst</h3>
        <label>Titel</label>
        <input class="mn-input" name="loading_title" value="<?= htmlspecialchars($settings['loading_title'] ?? ($settings['server_name'] ?? 'MemoNetwork')) ?>">

        <label>Subtitle</label>
        <input class="mn-input" name="loading_subtitle" value="<?= htmlspecialchars($settings['loading_subtitle'] ?? ($settings['server_subtitle'] ?? 'Industrial Sandbox')) ?>">

        <label>Tip</label>
        <input class="mn-input" name="loading_tip" value="<?= htmlspecialchars($settings['loading_tip'] ?? 'Welkom op MemoNetwork. Veel bouwplezier!') ?>">

        <h3>Achtergrond</h3>
        <label>Achtergrond URL of pad</label>
        <input class="mn-input" name="loading_background_url" value="<?= htmlspecialchars($settings['loading_background_url'] ?? '') ?>" placeholder="uploads/loading/background.jpg of https://...">

        <label>Achtergrond uploaden</label>
        <input class="mn-input" type="file" name="background_file" accept="image/*,video/mp4,video/webm">

        <h3>Muziek</h3>
        <label>Muziek URL of pad</label>
        <input class="mn-input" name="loading_music_url" value="<?= htmlspecialchars($settings['loading_music_url'] ?? '') ?>" placeholder="uploads/loading/music.mp3 of https://...">

        <label>Muziek uploaden</label>
        <input class="mn-input" type="file" name="music_file" accept="audio/*">

        <label>Volume</label>
        <input class="mn-input" type="number" min="0" max="100" name="loading_volume" value="<?= htmlspecialchars($settings['loading_volume'] ?? '35') ?>">

        <h3>Stijl</h3>
        <label>Accentkleur</label>
        <input class="mn-input" name="loading_accent_color" value="<?= htmlspecialchars($settings['loading_accent_color'] ?? '#ff9100') ?>">

        <label>Overlay transparantie 0-100</label>
        <input class="mn-input" type="number" min="0" max="100" name="loading_overlay_opacity" value="<?= htmlspecialchars($settings['loading_overlay_opacity'] ?? '55') ?>">

        <button class="mn-button" type="submit">Opslaan</button>
    </form>

    <section class="mn-card">
        <h3>Live Preview</h3>
        <?php
            $bg = $settings['loading_background_url'] ?? '';
            $accent = $settings['loading_accent_color'] ?? '#ff9100';
            $opacity = max(0, min(100, (int)($settings['loading_overlay_opacity'] ?? 55))) / 100;
        ?>
        <div class="mn-loading-preview" style="--preview-accent: <?= htmlspecialchars($accent) ?>; <?= $bg ? 'background-image: url(' . htmlspecialchars($bg) . ');' : '' ?>">
            <div class="mn-loading-overlay" style="background: rgba(0,0,0,<?= htmlspecialchars((string)$opacity) ?>)"></div>
            <div class="mn-loading-content">
                <div class="mn-loading-brand"><?= htmlspecialchars($settings['loading_title'] ?? ($settings['server_name'] ?? 'MemoNetwork')) ?></div>
                <div class="mn-loading-subtitle"><?= htmlspecialchars($settings['loading_subtitle'] ?? ($settings['server_subtitle'] ?? 'Industrial Sandbox')) ?></div>
                <div class="mn-loading-bar"><span></span></div>
                <p><?= htmlspecialchars($settings['loading_tip'] ?? 'Welkom op MemoNetwork. Veel bouwplezier!') ?></p>
                <div class="mn-loading-news">
                    <?php if (empty($news)): ?>
                        <div>Geen nieuws toegevoegd.</div>
                    <?php endif; ?>
                    <?php foreach ($news as $item): ?>
                        <div><strong><?= htmlspecialchars($item['title']) ?></strong><br><span><?= htmlspecialchars($item['body'] ?? '') ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <p class="mn-muted">Deze preview gebruikt dezelfde instellingen die straks via de API door MemoNetwork Core worden opgehaald.</p>
        <a class="mn-button" href="api/loading.php" target="_blank">Bekijk API JSON</a>
    </section>
</div>
