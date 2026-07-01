<?php use MemoNetwork\Core\Url; ?>
<div class="mn-login">
    <form class="mn-login-card" method="post" action="<?= htmlspecialchars(Url::to('install.php')) ?>">
        <div class="mn-logo">Memo<span>Network</span></div>
        <p class="mn-muted">Alpha 26 installer - database, owner-account en .env automatisch aanmaken.</p>

        <?php if (!empty($done)): ?>
            <div class="mn-card">
                <h3>Installatie klaar</h3>
                <p>Database is verbonden, tabellen zijn aangemaakt en het owner-account staat klaar.</p>
                <a class="mn-button" href="<?= htmlspecialchars(Url::to('login.php')) ?>">Naar login</a>
            </div>
        <?php else: ?>
            <?php if (!empty($error)): ?>
                <div class="mn-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <h3>Website</h3>
            <label>App URL</label>
            <input class="mn-input" type="text" name="app_url" value="<?= htmlspecialchars($defaults['app_url'] ?? 'https://memocraft.nl/adminpanel') ?>" required>

            <h3>Database</h3>
            <label>Database host</label>
            <input class="mn-input" type="text" name="db_host" value="<?= htmlspecialchars($defaults['db_host'] ?? 'localhost') ?>" required>

            <label>Database poort</label>
            <input class="mn-input" type="text" name="db_port" value="<?= htmlspecialchars($defaults['db_port'] ?? '3306') ?>" required>

            <label>Database naam</label>
            <input class="mn-input" type="text" name="db_name" value="<?= htmlspecialchars($defaults['db_name'] ?? '') ?>" required>

            <label>Database gebruiker</label>
            <input class="mn-input" type="text" name="db_user" value="<?= htmlspecialchars($defaults['db_user'] ?? '') ?>" required>

            <label>Database wachtwoord</label>
            <input class="mn-input" type="password" name="db_pass" autocomplete="new-password">

            <h3>Owner account</h3>
            <label>Owner gebruikersnaam</label>
            <input class="mn-input" type="text" name="owner_user" value="<?= htmlspecialchars($defaults['owner_user'] ?? 'owner') ?>" required>

            <label>Owner wachtwoord</label>
            <input class="mn-input" type="password" name="owner_pass" minlength="8" autocomplete="new-password" required>

            <button class="mn-button" type="submit">Installeren</button>
        <?php endif; ?>
    </form>
</div>
