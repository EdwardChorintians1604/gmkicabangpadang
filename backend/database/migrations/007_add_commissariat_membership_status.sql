-- Store commissariat membership separately from the commissariat name.
ALTER TABLE `civitas`
    ADD COLUMN `anggota_komisariat` TINYINT(1) NOT NULL DEFAULT 1 AFTER `komisariat`;

UPDATE `civitas`
SET `anggota_komisariat` = 0,
    `komisariat` = NULL
WHERE `komisariat` IS NULL OR `komisariat` = '';
