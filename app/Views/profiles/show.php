<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Player Profile</h1>
        <p class="mn-muted">Notes, warnings and event history.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 7</div>
</div>

<?php if (!$profile): ?>
    <div class="mn-error">Player profile not found.</div>
<?php else: ?>
    <?php if (!empty($saved)): ?><div class="mn-success">Profile saved.</div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="mn-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="mn-two-col">
        <section class="mn-card">
            <h3>Player</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($profile['player_name']) ?></p>
            <p><strong>SteamID:</strong> <?= htmlspecialchars($profile['steam_id']) ?></p>
            <p><strong>SteamID64:</strong> <?= htmlspecialchars($profile['steam_id64'] ?? '-') ?></p>
            <p><strong>First seen:</strong> <?= htmlspecialchars($profile['first_seen'] ?? '-') ?></p>
            <p><strong>Last seen:</strong> <?= htmlspecialchars($profile['last_seen'] ?? '-') ?></p>
            <p><strong>Total playtime:</strong> <?= (int)floor(((int)$profile['total_playtime_seconds']) / 60) ?> minutes</p>
        </section>

        <section class="mn-card mn-form">
            <h3>Staff Notes</h3>
            <form method="post" action="profile.php">
                <input type="hidden" name="steam_id" value="<?= htmlspecialchars($profile['steam_id']) ?>">
                <label>Warnings</label>
                <input class="mn-input" type="number" name="warnings" value="<?= (int)$profile['warnings'] ?>">
                <label>Notes</label>
                <textarea class="mn-input mn-textarea" name="notes"><?= htmlspecialchars($profile['notes'] ?? '') ?></textarea>
                <button class="mn-button" type="submit">Save Profile</button>
            </form>
        </section>
    </div>

    <section class="mn-card" style="margin-top:18px;">
        <h3>Recent Events</h3>
        <?php if (empty($events)): ?><p class="mn-muted">No events recorded yet.</p><?php endif; ?>
        <div class="mn-table-wrap">
            <table class="mn-table">
                <thead><tr><th>Event</th><th>Message</th><th>Time</th></tr></thead>
                <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['event_type']) ?></td>
                        <td><?= htmlspecialchars($event['message'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($event['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
