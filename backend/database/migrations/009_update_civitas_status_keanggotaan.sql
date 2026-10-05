-- Memperbarui tipe kolom status_keanggotaan agar fleksibel menampung status keanggotaan nyata organisasi
-- (Aktif, Pasif / Jarang Aktif, Alumni/Senior, Pindah Cabang, Dicabut)
ALTER TABLE `civitas`
    MODIFY `status_keanggotaan` VARCHAR(50) NOT NULL DEFAULT 'Aktif';

UPDATE `civitas`
SET `status_keanggotaan` = 'Pasif'
WHERE `status_keanggotaan` = 'Nonaktif';
