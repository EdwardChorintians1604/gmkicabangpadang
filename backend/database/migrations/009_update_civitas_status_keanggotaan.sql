-- Memperbarui tipe kolom status_keanggotaan dan mengganti alamat_asal menjadi asal_civitas
-- Status keanggotaan resmi: Aktif, Pasif, Alumni, Senior
ALTER TABLE `civitas`
    MODIFY `status_keanggotaan` VARCHAR(50) NOT NULL DEFAULT 'Aktif',
    CHANGE COLUMN `alamat_asal` `asal_civitas` VARCHAR(150) NULL;

UPDATE `civitas`
SET `status_keanggotaan` = 'Alumni'
WHERE `status_keanggotaan` = 'Alumni/Senior';

UPDATE `civitas`
SET `status_keanggotaan` = 'Pasif'
WHERE `status_keanggotaan` NOT IN ('Aktif', 'Pasif', 'Alumni', 'Senior');
