-- =====================================================================
-- GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
-- Migration: 014_update_proker_sekfung.sql
-- Deskripsi: Penambahan field pendataan Program Kerja (Proker) Sekfung
-- =====================================================================

ALTER TABLE `organization_strategies`
    ADD COLUMN `sekfung_name` VARCHAR(150) NULL AFTER `title`,
    ADD COLUMN `sekfung_jabatan` VARCHAR(150) NULL AFTER `sekfung_name`,
    ADD COLUMN `sekfung_wa` VARCHAR(30) NULL AFTER `sekfung_jabatan`,
    ADD COLUMN `dasar_pemikiran` TEXT NULL AFTER `sekfung_wa`,
    ADD COLUMN `activity_type` VARCHAR(120) NULL AFTER `objective`,
    ADD COLUMN `indicator` TEXT NULL AFTER `activity_type`,
    ADD COLUMN `is_achieved` ENUM('Iya', 'Tidak', 'Proses', 'Rencana') NOT NULL DEFAULT 'Rencana' AFTER `indicator`,
    ADD COLUMN `execution_time` VARCHAR(180) NULL AFTER `is_achieved`,
    ADD COLUMN `notes` TEXT NULL AFTER `execution_time`;

CREATE INDEX `idx_strategies_sekfung_achieved` ON `organization_strategies` (`sekfung_jabatan`, `is_achieved`);
