<div class="mn-login">
    <form class="mn-login-card" method="post" action="install.php">
        <div class="mn-logo">Memo<span>Network</span></div>
        <p class="mn-muted">Alpha 26 installer - maak het eerste owner-account aan.</p>

        <?php if (!empty($done)): ?>
            <div class="mn-card">
                <h3>Installatie klaar</h3>
                <p>Je kunt nu inloggen.</p>
                <a class="mn-button" href="login.php">Naar login</a>
            </div>
        <?php else: ?>
            <?php if (!empty($error)): ?>
                <div class="mn-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <label>Owner gebruikersnaam</label>
            <input class="mn-input" type="text" name="username" value="owner" required>

            <label>Owner wachtwoord</label>
            <input class="mn-input" type="password" name="password" minlength="8" required>

            <button class="mn-button" type="submit">Installeren</button>
        <?php endif; ?>
    </form>
</div>
