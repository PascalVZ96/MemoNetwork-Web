async function mnRefreshLiveStatus() {
    const root = document.querySelector('[data-live-dashboard]');
    if (!root) return;

    try {
        const response = await fetch('api/live-status.php', { cache: 'no-store' });
        const data = await response.json();
        if (!data.ok) return;

        const status = data.status || {};
        const players = data.players || [];
        const isOnline = Number(status.is_online || 0) === 1;

        const setText = (key, value) => {
            const el = root.querySelector(`[data-live="${key}"]`);
            if (el) el.textContent = value;
        };

        setText('server_status', isOnline ? 'Online' : 'Offline');
        setText('players', `${status.players_online || players.length || 0} / ${status.max_players || 0}`);
        setText('map', status.map_name || '-');
        setText('entities', status.entities || 0);
        setText('props', status.props || 0);
        setText('vehicles', status.vehicles || 0);
        setText('wire', status.wire_entities || 0);
        setText('fps', status.server_fps || '-');
        setText('ram', status.ram_mb ? `${status.ram_mb} MB` : '-');
        setText('cpu', status.cpu_percent ? `${status.cpu_percent}%` : '-');
        setText('uptime', status.uptime_seconds ? `${status.uptime_seconds}s` : '-');
        setText('health', status.health || (isOnline ? 'online' : 'offline'));
        setText('last_seen', status.last_seen || 'Never');

        const badge = root.querySelector('[data-live-badge]');
        if (badge) {
            badge.textContent = isOnline ? 'ONLINE' : 'OFFLINE';
            badge.className = isOnline ? 'mn-status-badge online' : 'mn-status-badge offline';
        }
    } catch (e) {
        console.warn('MemoNetwork live dashboard refresh failed', e);
    }
}

mnRefreshLiveStatus();
setInterval(mnRefreshLiveStatus, 5000);
