-- --------------------------------------------------------
-- 011_replace_ktb_with_anggota.sql
-- Menghapus istilah KTB dan menggantinya dengan 'Anggota' sah GMKI
-- --------------------------------------------------------

-- 1. Perluas ENUM untuk mencakup 'Anggota'
ALTER TABLE `civitas` 
MODIFY COLUMN `tingkat_kaderisasi` ENUM('Maperca', 'Anggota', 'KTB', 'KK', 'Alumni') NOT NULL DEFAULT 'Anggota';

-- 2. Migrasikan seluruh data KTB menjadi 'Anggota' sah
UPDATE `civitas` 
SET `tingkat_kaderisasi` = 'Anggota' 
WHERE `tingkat_kaderisasi` = 'KTB';

-- 3. Hapus 'KTB' dari ENUM secara permanen
ALTER TABLE `civitas` 
MODIFY COLUMN `tingkat_kaderisasi` ENUM('Maperca', 'Anggota', 'KK', 'Alumni') NOT NULL DEFAULT 'Anggota';
