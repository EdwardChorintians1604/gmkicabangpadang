-- Upgrade Tabel office_documents untuk Redundansi & Duplikasi Keamanan Berkas Sekcab
ALTER TABLE `office_documents`
ADD COLUMN `backup_stored_name` VARCHAR(255) NULL AFTER `stored_name`,
ADD COLUMN `file_hash` VARCHAR(64) NULL AFTER `backup_stored_name`,
ADD COLUMN `is_duplicated` TINYINT(1) NOT NULL DEFAULT 1 AFTER `file_hash`,
ADD COLUMN `duplicate_status` VARCHAR(60) NOT NULL DEFAULT 'Duplikasi Terverifikasi (Identik)' AFTER `is_duplicated`;
