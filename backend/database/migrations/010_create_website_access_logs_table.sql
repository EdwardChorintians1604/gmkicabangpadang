-- --------------------------------------------------------
-- 010_create_website_access_logs_table.sql
-- Audit Jejak Akses Pengunjung, IP, & Perangkat Website
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `website_access_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `username` VARCHAR(60) NULL DEFAULT 'Tamu Publik',
    `role` VARCHAR(30) NULL DEFAULT 'guest',
    `method` VARCHAR(10) NOT NULL DEFAULT 'GET',
    `path` VARCHAR(255) NOT NULL,
    `status_code` SMALLINT UNSIGNED NOT NULL DEFAULT 200,
    `device_type` VARCHAR(30) NOT NULL DEFAULT 'Desktop',
    `platform` VARCHAR(60) NOT NULL DEFAULT 'Unknown OS',
    `browser` VARCHAR(60) NOT NULL DEFAULT 'Unknown Browser',
    `user_agent` TEXT NULL,
    `referer` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_access_created` (`created_at`),
    INDEX `idx_access_ip` (`ip_address`),
    INDEX `idx_access_device` (`device_type`),
    INDEX `idx_access_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
