-- Allow initial Maperca/member registrations before their commissariat and intake year are assigned.
ALTER TABLE `civitas`
    MODIFY `komisariat` VARCHAR(100) NULL,
    MODIFY `tahun_maperca` YEAR NULL;
