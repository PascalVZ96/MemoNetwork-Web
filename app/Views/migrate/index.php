<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Database Update</h1>
        <p class="mn-muted">Apply new database tables for MemoNetwork Web.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 4</div>
</div>

<?php if (!empty($done)): ?>
    <div class="mn-success">Database updated successfully.</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="mn-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<section class="mn-card">
    <h3>Next step</h3>
    <p>Go back to the dashboard and test live monitoring.</p>
    <a class="mn-button" href="dashboard.php">Back to Dashboard</a>
</section>
