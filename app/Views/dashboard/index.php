<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Dashboard</h1>
        <p class="mn-muted">Welkom terug, <?= htmlspecialchars($user['username'] ?? 'admin') ?>.</p>
    </div>
    <div class="mn-muted">Alpha 26.0-dev</div>
</div>

<div class="mn-grid">
    <section class="mn-card">
        <h3>Server</h3>
        <div class="mn-value">Offline</div>
        <p class="mn-muted">Wacht op GMod API-koppeling.</p>
    </section>
    <section class="mn-card">
        <h3>Players</h3>
        <div class="mn-value">0</div>
        <p class="mn-muted">Live player sync komt hier.</p>
    </section>
    <section class="mn-card">
        <h3>Builds</h3>
        <div class="mn-value">0</div>
        <p class="mn-muted">Build browser voorbereiding.</p>
    </section>
    <section class="mn-card">
        <h3>Alerts</h3>
        <div class="mn-value">0</div>
        <p class="mn-muted">Automation alerts komen hier.</p>
    </section>
</div>

<section class="mn-card" style="margin-top:18px;">
    <h3>Alpha 26 foundation</h3>
    <p>De basis staat klaar: login, sessies, database, layout, schema en API-voorbereiding.</p>
</section>
