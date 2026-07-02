<div class="mn-topbar">
    <div>
        <h1 class="mn-title">Core Integration</h1>
        <p class="mn-muted">Connection details for MemoNetwork Core and future real-time integration.</p>
    </div>
    <div class="mn-muted">Alpha 26 Sprint 7</div>
</div>

<div class="mn-two-col">
    <section class="mn-card">
        <h3>API Settings</h3>
        <p><strong>Panel URL:</strong> <span class="mn-muted"><?= htmlspecialchars($baseUrl) ?></span></p>
        <p><strong>API Token:</strong> <span class="mn-muted"><?= htmlspecialchars($apiTokenPreview) ?></span></p>
        <p class="mn-muted">Use the real token from your .env file in MemoNetwork Core.</p>
    </section>

    <section class="mn-card">
        <h3>Core Polling Flow</h3>
        <p>1. Server posts status and metrics.</p>
        <p>2. Server posts player join/leave events.</p>
        <p>3. Server polls pending commands.</p>
        <p>4. Server acknowledges command results.</p>
    </section>
</div>

<section class="mn-card" style="margin-top:18px;">
    <h3>Endpoints</h3>
    <div class="mn-table-wrap">
        <table class="mn-table">
            <thead><tr><th>Method</th><th>Endpoint</th><th>Purpose</th></tr></thead>
            <tbody>
                <tr><td>POST</td><td><?= htmlspecialchars($baseUrl) ?>/api/server-status.php</td><td>Live server status</td></tr>
                <tr><td>POST</td><td><?= htmlspecialchars($baseUrl) ?>/api/metrics.php</td><td>Monitoring history and alerts</td></tr>
                <tr><td>POST</td><td><?= htmlspecialchars($baseUrl) ?>/api/console.php</td><td>Console log output</td></tr>
                <tr><td>POST</td><td><?= htmlspecialchars($baseUrl) ?>/api/player-event.php</td><td>Player join, leave and profile events</td></tr>
                <tr><td>GET</td><td><?= htmlspecialchars($baseUrl) ?>/api/commands.php</td><td>Poll pending commands</td></tr>
                <tr><td>POST</td><td><?= htmlspecialchars($baseUrl) ?>/api/commands.php</td><td>Acknowledge command result</td></tr>
                <tr><td>POST</td><td><?= htmlspecialchars($baseUrl) ?>/api/builds.php</td><td>Register builds</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section class="mn-card" style="margin-top:18px;">
    <h3>Example Header</h3>
    <pre class="mn-code">Authorization: Bearer YOUR_API_TOKEN</pre>
</section>
