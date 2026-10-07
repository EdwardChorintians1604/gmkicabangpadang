-- Mode Pengawas telah dihentikan. Akun lama tidak lagi dapat masuk sampai
-- administrator menetapkan salah satu role panel yang masih digunakan.
UPDATE `users`
SET `status` = 'nonaktif'
WHERE `role` = 'pengawas';
