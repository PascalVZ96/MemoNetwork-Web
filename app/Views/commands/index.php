<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Command Center</h1>
        <p class="mn-muted">Queue player actions and console commands for MemoNetwork Core.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 5</div>
</div>

<?php if (!empty($saved)): ?><div class="mn-success">Command queued successfully.</div><?php endif; ?>
<?php if (!empty($error)): ?><div class="mn-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="mn-two-col">
    <section class="mn-card mn-form">
        <h3>Queue Command</h3>
        <form method="post" action="commands.php">
            <label>Command Type</label>
            <select class="mn-input" name="command_type" required>
                <?php foreach (['kick','ban','mute','unmute','gag','ungag','freeze','unfreeze','ignite','slay','heal','bring','goto','spectate','console'] as $type): ?>
                    <option value="<?= htmlspecialchars($type) ?>"><?= strtoupper(htmlspecialchars($type)) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Target Player</label>
            <select class="mn-input" onchange="const o=this.options[this.selectedIndex]; document.querySelector('[name=target_steam_id]').value=o.dataset.steam||''; document.querySelector('[name=target_name]').value=o.dataset.name||'';">
                <option value="">Manual / Console command</option>
                <?php foreach ($players as $player): ?>
                    <option data-steam="<?= htmlspecialchars($player['steam_id']) ?>" data-name="<?= htmlspecialchars($player['player_name']) ?>">
                        <?= htmlspecialchars($player['player_name']) ?> — <?= htmlspecialchars($player['steam_id']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Target SteamID</label>
            <input class="mn-input" name="target_steam_id" placeholder="STEAM_0:1:...">

            <label>Target Name</label>
            <input class="mn-input" name="target_name" placeholder="Player name">

            <label>Payload / Reason / Console Command</label>
            <textarea class="mn-input mn-textarea" name="payload" placeholder="Reason or console command..."></textarea>

            <button class="mn-button" type="submit">Queue Command</button>
        </form>
    </section>

    <section class="mn-card">
        <h3>How it works</h3>
        <p>Commands are stored in the command queue. MemoNetwork Core can poll the API endpoint and execute pending commands in-game.</p>
        <p class="mn-muted">This sprint prepares the web side. The next Core update will connect the queue to Garry's Mod.</p>
        <a class="mn-button" href="api/commands.php" target="_blank">View Command API</a>
    </section>
</div>

<section class="mn-card" style="margin-top:18px;">
    <h3>Recent Commands</h3>
    <div class="mn-table-wrap">
        <table class="mn-table">
            <thead><tr><th>ID</th><th>Type</th><th>Target</th><th>Status</th><th>Created By</th><th>Created</th></tr></thead>
            <tbody>
            <?php foreach ($commands as $cmd): ?>
                <tr>
                    <td><?= (int)$cmd['id'] ?></td>
                    <td><?= htmlspecialchars(strtoupper($cmd['command_type'])) ?></td>
                    <td><?= htmlspecialchars($cmd['target_name'] ?: $cmd['target_steam_id'] ?: 'Server') ?></td>
                    <td><?= htmlspecialchars($cmd['status']) ?></td>
                    <td><?= htmlspecialchars($cmd['created_by'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($cmd['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
