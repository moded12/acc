-- Path: /database/migrations/database.sql
CREATE DATABASE IF NOT EXISTS `accounting` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `accounting`;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    permission_key VARCHAR(150) NOT NULL UNIQUE,
    created_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY(role_id, permission_id),
    CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_rp_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NULL,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NULL,
    INDEX idx_users_role_id (role_id),
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL
) ENGINE=InnoDB;

INSERT INTO roles (id, name, created_at) VALUES
(1, 'Super Admin', NOW()),
(2, 'Manager', NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO permissions (id, name, permission_key, created_at) VALUES
(1, 'View Dashboard', 'dashboard.view', NOW()),
(2, 'Manage Users', 'users.manage', NOW()),
(3, 'Manage Settings', 'settings.manage', NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name), permission_key = VALUES(permission_key);

INSERT INTO role_permissions (role_id, permission_id) VALUES
(1,1),(1,2),(1,3),(2,1)
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);

INSERT INTO users (id, role_id, name, email, password, status, created_at) VALUES
(1, 1, 'System Admin', 'admin@erp.local', '$2y$12$j961TsOUn.b1OX4AoifsmenNX.Ba.vUhBnSXEtBF6O/0S1i2ELXyq', 'active', NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name), email = VALUES(email), role_id = VALUES(role_id), status = VALUES(status), password = VALUES(password);

INSERT INTO settings (setting_key, setting_value) VALUES
('company_name', 'Shneler ERP'),
('currency', 'USD'),
('invoice_prefix', 'INV'),
('default_tax', '16')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
