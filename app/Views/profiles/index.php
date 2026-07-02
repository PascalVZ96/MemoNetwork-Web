<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Player Profiles</h1>
        <p class="mn-muted">Persistent player records, playtime, notes and event history.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 7</div>
</div>

<section class="mn-card mn-form">
    <h3>Search Players</h3>
    <form method="get" action="profiles.php" class="mn-inline-form">
        <input class="mn-input" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search by name, SteamID or SteamID64">
        <button class="mn-button" type="submit">Search</button>
        <a class="mn-button" href="profiles.php">Reset</a>
    </form>
</section>

<section class="mn-card" style="margin-top:18px;">
    <h3>Profiles</h3>
    <?php if (empty($profiles)): ?><p class="mn-muted">No player profiles received yet.</p><?php endif; ?>
    <div class="mn-table-wrap">
        <table class="mn-table">
            <thead><tr><th>Player</th><th>SteamID</th><th>SteamID64</th><th>First Seen</th><th>Last Seen</th><th>Playtime</th><th>Warnings</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($profiles as $profile): ?>
                <tr>
                    <td><?= htmlspecialchars($profile['player_name']) ?></td>
                    <td><?= htmlspecialchars($profile['steam_id']) ?></td>
                    <td><?= htmlspecialchars($profile['steam_id64'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($profile['first_seen'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($profile['last_seen'] ?? '-') ?></td>
                    <td><?= (int)floor(((int)$profile['total_playtime_seconds']) / 60) ?> min</td>
                    <td><?= (int)$profile['warnings'] ?></td>
                    <td><a class="mn-button" href="profile.php?steam_id=<?= urlencode($profile['steam_id']) ?>">Open</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
