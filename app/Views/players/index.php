<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Players</h1>
        <p class="mn-muted">Live players reported by MemoNetwork Core.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 4</div>
</div>

<section class="mn-card">
    <h3>Server</h3>
    <p><strong>Status:</strong> <span class="<?= !empty($status['is_online']) ? 'mn-online' : 'mn-offline' ?>"><?= !empty($status['is_online']) ? 'Online' : 'Offline' ?></span></p>
    <p><strong>Map:</strong> <span class="mn-muted"><?= htmlspecialchars($status['map_name'] ?? '-') ?></span></p>
    <p><strong>Players:</strong> <span class="mn-muted"><?= (int)($status['players_online'] ?? 0) ?> / <?= (int)($status['max_players'] ?? 0) ?></span></p>
</section>

<section class="mn-card" style="margin-top:18px;">
    <h3>Player List</h3>
    <?php if (empty($players)): ?>
        <p class="mn-muted">No players reported yet.</p>
    <?php endif; ?>
    <div class="mn-table-wrap">
        <table class="mn-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>SteamID</th>
                <th>SteamID64</th>
                <th>Ping</th>
                <th>Team</th>
                <th>Connected</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($players as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player['player_name']) ?></td>
                    <td><?= htmlspecialchars($player['steam_id']) ?></td>
                    <td><?= htmlspecialchars($player['steam_id64'] ?? '-') ?></td>
                    <td><?= htmlspecialchars((string)($player['ping'] ?? '-')) ?></td>
                    <td><?= htmlspecialchars($player['team_name'] ?? '-') ?></td>
                    <td><?= (int)($player['connected_seconds'] ?? 0) ?>s</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
