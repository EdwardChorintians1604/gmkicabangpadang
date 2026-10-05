-- =====================================================================
-- Migrasi: 003_create_news_table.sql
-- Tabel Berita, Warta, dan Artikel GMKI Cabang Padang
-- =====================================================================

CREATE TABLE IF NOT EXISTS `berita` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `kategori` VARCHAR(60) NOT NULL DEFAULT 'Warta Cabang',
    `ringkasan` VARCHAR(500) NULL,
    `konten` LONGTEXT NOT NULL,
    `gambar_sampul` VARCHAR(255) NULL,
    `user_id` INT UNSIGNED NULL,
    `penulis_nama` VARCHAR(100) NULL,
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    `views` INT UNSIGNED NOT NULL DEFAULT 0,
    `published_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_berita_slug` (`slug`),
    INDEX `idx_berita_kategori` (`kategori`),
    INDEX `idx_berita_status` (`status`),
    INDEX `idx_berita_published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
