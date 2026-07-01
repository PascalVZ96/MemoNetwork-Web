<div class="mn-login">
    <form class="mn-login-card" method="post" action="/login.php">
        <div class="mn-logo">Memo<span>Network</span></div>
        <p class="mn-muted">Log in op het MemoNetwork Control Panel.</p>

        <?php if (!empty($error)): ?>
            <div class="mn-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <label>Gebruikersnaam</label>
        <input class="mn-input" type="text" name="username" autocomplete="username" required>

        <label>Wachtwoord</label>
        <input class="mn-input" type="password" name="password" autocomplete="current-password" required>

        <button class="mn-button" type="submit">Inloggen</button>
    </form>
</div>
