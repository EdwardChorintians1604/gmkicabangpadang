-- Upgrade Tabel office_documents untuk Administrasi Persuratan & Arsip Sekcab GMKI
ALTER TABLE `office_documents`
ADD COLUMN `letter_number` VARCHAR(120) NULL AFTER `document_type`,
ADD COLUMN `letter_category` VARCHAR(60) NOT NULL DEFAULT 'Surat Masuk' AFTER `letter_number`,
ADD COLUMN `sender` VARCHAR(180) NULL AFTER `title`,
ADD COLUMN `recipient` VARCHAR(180) NULL AFTER `sender`,
ADD COLUMN `letter_date` DATE NULL AFTER `recipient`,
ADD COLUMN `received_or_sent_date` DATE NULL AFTER `letter_date`,
ADD COLUMN `letter_nature` VARCHAR(40) NOT NULL DEFAULT 'Biasa' AFTER `received_or_sent_date`,
ADD COLUMN `status` VARCHAR(40) NOT NULL DEFAULT 'Diarsipkan' AFTER `letter_nature`,
ADD INDEX `idx_office_docs_category` (`letter_category`),
ADD INDEX `idx_office_docs_letter_date` (`letter_date`);
