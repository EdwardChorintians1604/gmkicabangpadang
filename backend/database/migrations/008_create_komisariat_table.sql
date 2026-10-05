-- Master data komisariat (dikelola lewat menu Komisariat).
-- Anggota (civitas) tetap menyimpan nama komisariat pada kolom civitas.komisariat.
-- Jalankan sekali. Pastikan migrasi 007 (kolom anggota_komisariat) sudah dijalankan.
CREATE TABLE IF NOT EXISTS `komisariat` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(150) NOT NULL,
    `perguruan_tinggi` VARCHAR(150) NULL,
    `keterangan` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_komisariat_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Masukkan komisariat yang sudah dipakai anggota
INSERT IGNORE INTO `komisariat` (`nama`)
SELECT DISTINCT `komisariat` FROM `civitas`
WHERE `komisariat` IS NOT NULL AND `komisariat` != '';
