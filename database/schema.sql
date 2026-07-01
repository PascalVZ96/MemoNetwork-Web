CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('owner','admin','moderator') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL UNIQUE,
    setting_value MEDIUMTEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS loading_news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    body TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS api_tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS server_status (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL UNIQUE,
    server_name VARCHAR(120) NOT NULL DEFAULT 'MemoNetwork',
    is_online TINYINT(1) NOT NULL DEFAULT 0,
    map_name VARCHAR(120) NULL,
    players_online INT NOT NULL DEFAULT 0,
    max_players INT NOT NULL DEFAULT 0,
    entities INT NOT NULL DEFAULT 0,
    props INT NOT NULL DEFAULT 0,
    vehicles INT NOT NULL DEFAULT 0,
    wire_entities INT NOT NULL DEFAULT 0,
    server_fps DECIMAL(8,2) NULL,
    ram_mb INT NULL,
    cpu_percent DECIMAL(8,2) NULL,
    uptime_seconds INT NULL,
    health VARCHAR(40) NOT NULL DEFAULT 'offline',
    last_seen TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS server_players (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL,
    steam_id VARCHAR(40) NOT NULL,
    steam_id64 VARCHAR(40) NULL,
    player_name VARCHAR(120) NOT NULL,
    ping INT NULL,
    team_name VARCHAR(80) NULL,
    connected_seconds INT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_server_player (server_key, steam_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS command_queue (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    command_type VARCHAR(80) NOT NULL,
    target_steam_id VARCHAR(40) NULL,
    target_name VARCHAR(120) NULL,
    payload TEXT NULL,
    status ENUM('pending','sent','done','failed') NOT NULL DEFAULT 'pending',
    result TEXT NULL,
    created_by VARCHAR(80) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS action_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    action_type VARCHAR(80) NOT NULL,
    actor VARCHAR(120) NULL,
    target VARCHAR(160) NULL,
    message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS builds (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    build_name VARCHAR(160) NOT NULL,
    owner_name VARCHAR(120) NULL,
    owner_steam_id VARCHAR(40) NULL,
    map_name VARCHAR(120) NULL,
    props INT NOT NULL DEFAULT 0,
    vehicles INT NOT NULL DEFAULT 0,
    wire_entities INT NOT NULL DEFAULT 0,
    performance_score INT NOT NULL DEFAULT 100,
    preview_url VARCHAR(255) NULL,
    file_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS console_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    level VARCHAR(30) NOT NULL DEFAULT 'info',
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS monitoring_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    players_online INT NOT NULL DEFAULT 0,
    entities INT NOT NULL DEFAULT 0,
    props INT NOT NULL DEFAULT 0,
    server_fps DECIMAL(8,2) NULL,
    ram_mb INT NULL,
    cpu_percent DECIMAL(8,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS alerts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_key VARCHAR(80) NOT NULL DEFAULT 'main',
    severity ENUM('info','warning','critical') NOT NULL DEFAULT 'info',
    title VARCHAR(160) NOT NULL,
    message TEXT NULL,
    is_resolved TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('server_name', 'MemoNetwork'),
('server_subtitle', 'Industrial Sandbox'),
('server_version', 'Alpha 26.0-dev'),
('website_url', 'https://memocraft.nl'),
('discord_url', 'https://memocraft.nl/?c=Discord'),
('footer_text', 'Welcome to MemoNetwork'),
('alert_cpu_percent', '90'),
('alert_ram_mb', '4096'),
('alert_min_fps', '20');

INSERT IGNORE INTO server_status (server_key, server_name, is_online, health) VALUES
('main', 'MemoNetwork', 0, 'offline');
