<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Build Browser</h1>
        <p class="mn-muted">Browse, search and queue build actions for MemoNetwork Core.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 5</div>
</div>

<?php if (!empty($saved)): ?><div class="mn-success">Build action completed.</div><?php endif; ?>
<?php if (!empty($error)): ?><div class="mn-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<section class="mn-card mn-form">
    <h3>Search Builds</h3>
    <form method="get" action="builds.php" class="mn-inline-form">
        <input class="mn-input" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search by build name, owner or SteamID">
        <button class="mn-button" type="submit">Search</button>
        <a class="mn-button" href="api/builds.php" target="_blank">Build API</a>
    </form>
</section>

<section class="mn-card" style="margin-top:18px;">
    <h3>Builds</h3>
    <?php if (empty($builds)): ?>
        <p class="mn-muted">No builds reported yet. MemoNetwork Core will add builds through the API later.</p>
    <?php endif; ?>
    <div class="mn-build-grid">
        <?php foreach ($builds as $build): ?>
            <article class="mn-build-card">
                <div class="mn-build-preview" style="<?= !empty($build['preview_url']) ? 'background-image:url(' . htmlspecialchars($build['preview_url']) . ')' : '' ?>">
                    <?php if (empty($build['preview_url'])): ?><span>No Preview</span><?php endif; ?>
                </div>
                <div class="mn-build-body">
                    <h3><?= htmlspecialchars($build['build_name']) ?></h3>
                    <p class="mn-muted">Owner: <?= htmlspecialchars($build['owner_name'] ?? '-') ?></p>
                    <p class="mn-muted">Map: <?= htmlspecialchars($build['map_name'] ?? '-') ?></p>
                    <p>Props: <?= (int)$build['props'] ?> | Vehicles: <?= (int)$build['vehicles'] ?> | Wire: <?= (int)$build['wire_entities'] ?></p>
                    <p>Performance Score: <strong><?= (int)$build['performance_score'] ?></strong></p>
                    <div class="mn-actions">
                        <form method="post" action="builds.php">
                            <input type="hidden" name="action" value="spawn">
                            <input type="hidden" name="id" value="<?= (int)$build['id'] ?>">
                            <button class="mn-button" type="submit">Queue Spawn</button>
                        </form>
                        <?php if (!empty($build['file_url'])): ?><a class="mn-button" href="<?= htmlspecialchars($build['file_url']) ?>">Download</a><?php endif; ?>
                        <form method="post" action="builds.php" onsubmit="return confirm('Delete this build?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$build['id'] ?>">
                            <button class="mn-button danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
