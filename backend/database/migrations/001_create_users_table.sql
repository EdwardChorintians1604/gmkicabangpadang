-- =====================================================================
-- Migrasi: 001_create_users_table.sql
-- Tabel Pengguna & Hak Akses GMKI Cabang Padang
-- =====================================================================

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `role` ENUM('admin', 'pengawas', 'operator') NOT NULL DEFAULT 'operator',
    `status` ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    `remember_token` VARCHAR(100) NULL,
    `last_login_at` DATETIME NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
