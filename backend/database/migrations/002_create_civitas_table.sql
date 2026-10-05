-- =====================================================================
-- Migrasi: 002_create_civitas_table.sql
-- Tabel Civitas / Data Keanggotaan GMKI Cabang Padang
-- =====================================================================

CREATE TABLE IF NOT EXISTS `civitas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nim` VARCHAR(30) NOT NULL UNIQUE,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `jenis_kelamin` ENUM('L', 'P') NOT NULL,
    `tempat_lahir` VARCHAR(100) NULL,
    `tanggal_lahir` DATE NULL,
    `telepon` VARCHAR(25) NULL,
    `email` VARCHAR(100) NULL,
    `perguruan_tinggi` VARCHAR(150) NOT NULL,
    `fakultas` VARCHAR(100) NULL,
    `jurusan` VARCHAR(100) NULL,
    `komisariat` VARCHAR(100) NOT NULL,
    `tahun_maperca` YEAR NOT NULL,
    `tingkat_kaderisasi` ENUM('Maperca', 'KTB', 'KK', 'Alumni') NOT NULL DEFAULT 'Maperca',
    `status_keanggotaan` ENUM('Aktif', 'Alumni/Senior', 'Pindah Cabang', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
    `alamat_padang` TEXT NULL,
    `alamat_asal` TEXT NULL,
    `foto_anggota` VARCHAR(255) NULL,
    `file_kta` VARCHAR(255) NULL,
    `catatan` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_civitas_komisariat` (`komisariat`),
    INDEX `idx_civitas_pt` (`perguruan_tinggi`),
    INDEX `idx_civitas_tahun_maperca` (`tahun_maperca`),
    INDEX `idx_civitas_status` (`status_keanggotaan`),
    INDEX `idx_civitas_nama` (`nama_lengkap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
