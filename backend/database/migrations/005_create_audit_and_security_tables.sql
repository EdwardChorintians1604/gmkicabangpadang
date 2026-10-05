-- =====================================================================
-- Migrasi: 005_create_audit_and_security_tables.sql
-- Tabel Jejak Audit, Deteksi Ancaman, & Keamanan Sistem
-- =====================================================================

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `username` VARCHAR(60) NULL,
    `role` VARCHAR(30) NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity` VARCHAR(60) NOT NULL,
    `entity_id` VARCHAR(50) NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `security_threats` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `threat_type` VARCHAR(60) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `payload` TEXT NULL,
    `severity` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
    `status` ENUM('detected', 'blocked', 'resolved') NOT NULL DEFAULT 'detected',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_threats_ip` (`ip_address`),
    INDEX `idx_threats_type` (`threat_type`),
    INDEX `idx_threats_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `username` VARCHAR(60) NOT NULL,
    `is_success` TINYINT(1) NOT NULL DEFAULT 0,
    `user_agent` VARCHAR(255) NULL,
    `attempted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_attempts_ip_time` (`ip_address`, `attempted_at`),
    INDEX `idx_attempts_user_time` (`username`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
