<div class="mn-topbar">
    <div>
        <h1 class="mn-title">News Manager</h1>
        <p class="mn-muted">Beheer nieuwsregels voor dashboard en later het loading screen.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 2</div>
</div>

<?php if (!empty($saved)): ?>
    <div class="mn-success">Wijziging opgeslagen.</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="mn-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<section class="mn-card mn-form">
    <h3>Nieuw bericht</h3>
    <form method="post" action="news.php">
        <input type="hidden" name="action" value="create">
        <label>Titel</label>
        <input class="mn-input" name="title" placeholder="Bijvoorbeeld: Alpha 26 is live" required>
        <label>Bericht</label>
        <textarea class="mn-input mn-textarea" name="body" placeholder="Korte tekst voor spelers..."></textarea>
        <label>Volgorde</label>
        <input class="mn-input" type="number" name="sort_order" value="0">
        <button class="mn-button" type="submit">Toevoegen</button>
    </form>
</section>

<section class="mn-card" style="margin-top:18px;">
    <h3>Bestaande berichten</h3>
    <?php if (empty($news)): ?>
        <p class="mn-muted">Nog geen nieuws toegevoegd.</p>
    <?php endif; ?>
    <div class="mn-list">
        <?php foreach ($news as $item): ?>
            <div class="mn-list-item">
                <div>
                    <strong><?= htmlspecialchars($item['title']) ?></strong>
                    <p class="mn-muted"><?= nl2br(htmlspecialchars($item['body'] ?? '')) ?></p>
                    <small class="mn-muted">Sort: <?= (int)$item['sort_order'] ?> | <?= !empty($item['is_active']) ? 'Actief' : 'Uitgeschakeld' ?></small>
                </div>
                <div class="mn-actions">
                    <form method="post" action="news.php">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                        <button class="mn-button" type="submit"><?= !empty($item['is_active']) ? 'Uitzetten' : 'Aanzetten' ?></button>
                    </form>
                    <form method="post" action="news.php" onsubmit="return confirm('Nieuws verwijderen?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                        <button class="mn-button danger" type="submit">Verwijderen</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
