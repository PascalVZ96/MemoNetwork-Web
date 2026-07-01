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

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('server_name', 'MemoNetwork'),
('server_subtitle', 'Industrial Sandbox'),
('server_version', 'Alpha 26.0-dev'),
('website_url', 'https://memocraft.nl'),
('discord_url', 'https://memocraft.nl/?c=Discord'),
('footer_text', 'Welcome to MemoNetwork');

INSERT IGNORE INTO server_status (server_key, server_name, is_online, health) VALUES
('main', 'MemoNetwork', 0, 'offline');
