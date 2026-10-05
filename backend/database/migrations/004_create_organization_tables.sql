-- =====================================================================
-- Migrasi: 004_create_organization_tables.sql
-- Tabel Profil Organisasi dan Struktur Kepengurusan BPC
-- =====================================================================

CREATE TABLE IF NOT EXISTS `profil_organisasi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_organisasi` VARCHAR(150) NOT NULL DEFAULT 'GMKI Cabang Padang',
    `slogan` VARCHAR(255) NOT NULL DEFAULT 'Ut Omnes Unum Sint - Syalom!',
    `tema_periode` VARCHAR(255) NULL,
    `sub_tema` VARCHAR(255) NULL,
    `sejarah` LONGTEXT NULL,
    `visi` TEXT NULL,
    `misi` TEXT NULL,
    `tri_panji` TEXT NULL,
    `panca_kegiatan` TEXT NULL,
    `alamat_sekretariat` TEXT NULL,
    `telepon` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `instagram` VARCHAR(100) NULL,
    `youtube` VARCHAR(100) NULL,
    `facebook` VARCHAR(100) NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `struktur_organisasi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(150) NOT NULL,
    `jabatan` VARCHAR(100) NOT NULL,
    `bidang` VARCHAR(100) NOT NULL DEFAULT 'Badan Pengurus Harian',
    `periode` VARCHAR(50) NOT NULL DEFAULT '2024-2026',
    `urutan` INT NOT NULL DEFAULT 0,
    `foto` VARCHAR(255) NULL,
    `telepon` VARCHAR(25) NULL,
    `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_struktur_periode` (`periode`),
    INDEX `idx_struktur_urutan` (`urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
