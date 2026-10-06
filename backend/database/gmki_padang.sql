-- =====================================================================
-- GMKI CABANG PADANG - MASTER DATABASE SCHEMA & INITIAL DATA
-- Database: gmki_padang
-- Waktu Pembuatan: 2026-10-04
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- 1. TABEL USERS (PENGGUNA SISTEM)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `role` ENUM('admin', 'pengawas', 'operator') NOT NULL DEFAULT 'operator',
    `status` ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    `remember_token` VARCHAR(100) NULL,
    `last_login_at` DATETIME NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `created_at` DATETIME NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 2. TABEL CIVITAS (DATA ANGGOTA & KADER)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `civitas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nim` VARCHAR(30) NOT NULL UNIQUE,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `jenis_kelamin` ENUM('L', 'P') NOT NULL,
    `tempat_lahir` VARCHAR(100) NULL,
    `tanggal_lahir` DATE NULL,
    `telepon` VARCHAR(25) NULL,
    `email` VARCHAR(100) NULL,
    `perguruan_tinggi` VARCHAR(150) NOT NULL,
    `fakultas` VARCHAR(100) NULL,
    `jurusan` VARCHAR(100) NULL,
    `komisariat` VARCHAR(100) NULL,
    `anggota_komisariat` TINYINT(1) NOT NULL DEFAULT 1,
    `tahun_maperca` YEAR NULL,
    `tingkat_kaderisasi` ENUM('Maperca', 'KTB', 'KK', 'Alumni') NOT NULL DEFAULT 'Maperca',
    `status_keanggotaan` ENUM('Aktif', 'Alumni/Senior', 'Pindah Cabang', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
    `alamat_padang` TEXT NULL,
    `alamat_asal` TEXT NULL,
    `foto_anggota` VARCHAR(255) NULL,
    `file_kta` VARCHAR(255) NULL,
    `catatan` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` DATETIME NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_civitas_komisariat` (`komisariat`),
    INDEX `idx_civitas_pt` (`perguruan_tinggi`),
    INDEX `idx_civitas_tahun_maperca` (`tahun_maperca`),
    INDEX `idx_civitas_status` (`status_keanggotaan`),
    INDEX `idx_civitas_nama` (`nama_lengkap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 3. TABEL BERITA & WARTA
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `berita` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(190) NOT NULL UNIQUE,
    `kategori` VARCHAR(60) NOT NULL DEFAULT 'Warta Cabang',
    `ringkasan` VARCHAR(500) NULL,
    `konten` LONGTEXT NOT NULL,
    `gambar_sampul` VARCHAR(255) NULL,
    `user_id` INT UNSIGNED NULL,
    `penulis_nama` VARCHAR(100) NULL,
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    `views` INT UNSIGNED NOT NULL DEFAULT 0,
    `published_at` DATETIME NULL,
    `created_at` DATETIME NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_berita_slug` (`slug`),
    INDEX `idx_berita_kategori` (`kategori`),
    INDEX `idx_berita_status` (`status`),
    INDEX `idx_berita_published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 4. TABEL PROFIL ORGANISASI
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `profil_organisasi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_organisasi` VARCHAR(150) NOT NULL DEFAULT 'GMKI Cabang Padang',
    `slogan` VARCHAR(255) NOT NULL DEFAULT 'Ut Omnes Unum Sint - Syalom!',
    `tema_periode` VARCHAR(255) NULL,
    `sub_tema` VARCHAR(255) NULL,
    `sejarah` LONGTEXT NULL,
    `visi` TEXT NULL,
    `misi` TEXT NULL,
    `tri_panji` TEXT NULL,
    `panca_kegiatan` TEXT NULL,
    `alamat_sekretariat` TEXT NULL,
    `telepon` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `instagram` VARCHAR(100) NULL,
    `youtube` VARCHAR(100) NULL,
    `facebook` VARCHAR(100) NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 5. TABEL STRUKTUR PENGURUS BPC
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `struktur_organisasi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(150) NOT NULL,
    `jabatan` VARCHAR(100) NOT NULL,
    `bidang` VARCHAR(100) NOT NULL DEFAULT 'Badan Pengurus Harian',
    `periode` VARCHAR(50) NOT NULL DEFAULT '2024-2026',
    `urutan` INT NOT NULL DEFAULT 0,
    `foto` VARCHAR(255) NULL,
    `telepon` VARCHAR(25) NULL,
    `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_struktur_periode` (`periode`),
    INDEX `idx_struktur_urutan` (`urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 6. TABEL AUDIT LOGS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `username` VARCHAR(60) NULL,
    `role` VARCHAR(30) NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity` VARCHAR(60) NOT NULL,
    `entity_id` VARCHAR(50) NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 7. TABEL KEAMANAN & ANCAMAN
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `security_threats` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `threat_type` VARCHAR(60) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `payload` TEXT NULL,
    `severity` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
    `status` ENUM('detected', 'blocked', 'resolved') NOT NULL DEFAULT 'detected',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_threats_ip` (`ip_address`),
    INDEX `idx_threats_type` (`threat_type`),
    INDEX `idx_threats_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- 8. TABEL LOGIN ATTEMPTS (BRUTE FORCE DEFENSE)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `username` VARCHAR(60) NOT NULL,
    `is_success` TINYINT(1) NOT NULL DEFAULT 0,
    `user_agent` VARCHAR(255) NULL,
    `attempted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_attempts_ip_time` (`ip_address`, `attempted_at`),
    INDEX `idx_attempts_user_time` (`username`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- DATA AWAL (SEEDS)
-- --------------------------------------------------------

-- Default login: admin / Admin@GMKI2026!
INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'admin@gmkicabangpadang.or.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator BPC GMKI Padang', 'admin', 'aktif', NOW()),
(2, 'pengawas', 'pengawas@gmkicabangpadang.or.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Majelis Pengawas / Pertimbangan', 'pengawas', 'aktif', NOW()),
(3, 'operator', 'operator@gmkicabangpadang.or.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator Data Cabang', 'operator', 'aktif', NOW())
ON DUPLICATE KEY UPDATE `id` = `id`;

INSERT INTO `profil_organisasi` (`id`, `nama_organisasi`, `slogan`, `tema_periode`, `sub_tema`, `sejarah`, `visi`, `misi`, `tri_panji`, `panca_kegiatan`, `alamat_sekretariat`, `telepon`, `email`, `instagram`, `youtube`, `facebook`) VALUES
(1,
 'GMKI Cabang Padang',
 'Ut Omnes Unum Sint (Agar Mereka Semua Menjadi Satu) - Syalom!',
 'Bangkitlah, Menjadi Teranglah! (Yesaya 60:1)',
 'Memperkokoh Ketahanan Civitas dan Pelayanan di Tiga Medan Layan GMKI',
 'Gerakan Mahasiswa Kristen Indonesia (GMKI) Cabang Padang merupakan bagian integral dari persekutuan dan perjuangan mahasiswa Kristen di Kota Padang, Sumatera Barat. Berdiri sebagai wadah oikumenis dan nasionalis untuk mempersiapkan kader-kader pemimpin gereja, perguruan tinggi, dan masyarakat.',
 'Terwujudnya kedamaian, keadilan, kebenaran dan kesejahteraan bagi sesama manusia dan alam semesta berdasarkan kasih Yesus Kristus.',
 '1. Menumbuhkan kesadaran iman, karakter Kristiani, dan integritas tinggi bagi mahasiswa Kristen di Padang.\n2. Melaksanakan kaderisasi yang berkelanjutan dan kontekstual.\n3. Berpartisipasi aktif dalam kegiatan sosial, kebangsaan, dan oikoumene di Sumatera Barat.',
 '1. Tinggi Iman\n2. Tinggi Ilmu\n3. Tinggi Pengabdian',
 '1. Berdoa / Beribadah\n2. Belajar\n3. Bersaksi\n4. Bersosialisasi\n5. Berjuang',
 'Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Bar., kodya padang, Sumatera Barat',
 '+62 812-3456-7890',
 'sekretariat@gmkicabangpadang.or.id',
 '@gmkicabangpadang',
 'GMKI Cabang Padang Official',
 'GMKI Cabang Padang'
)
ON DUPLICATE KEY UPDATE `id` = `id`;

INSERT INTO `struktur_organisasi` (`id`, `nama`, `jabatan`, `bidang`, `periode`, `urutan`, `telepon`, `status_aktif`) VALUES
(1, 'Yeremia Pratama, S.T.', 'Ketua Cabang', 'BPC (Badan Pengurus Cabang)', '2024-2026', 1, '081234567891', 1),
(2, 'Debora Silalahi, S.Ked.', 'Sekretaris Cabang', 'BPC (Badan Pengurus Cabang)', '2024-2026', 2, '081234567892', 1),
(3, 'Samuel Tampubolon, S.E.', 'Bendahara Cabang', 'BPC (Badan Pengurus Cabang)', '2024-2026', 3, '081234567893', 1),
(4, 'Grace Novita Hutapea', 'KABID OR', 'BPC (Badan Pengurus Cabang)', '2024-2026', 4, '081234567894', 1),
(5, 'Daniel Kristianto', 'KABID PKK', 'BPC (Badan Pengurus Cabang)', '2024-2026', 5, '081234567895', 1),
(6, 'Ruth Marbun', 'KABID AKSPEL', 'BPC (Badan Pengurus Cabang)', '2024-2026', 6, '081234567896', 1)
ON DUPLICATE KEY UPDATE `id` = `id`;

INSERT INTO `berita` (`id`, `judul`, `slug`, `kategori`, `ringkasan`, `konten`, `penulis_nama`, `status`, `views`, `published_at`) VALUES
(1,
 'Masa Perkenalan Calon Anggota (Maperca) GMKI Padang Tahun 2026 Sukses Digelar',
 'maperca-gmki-padang-tahun-2026-sukses-digelar',
 'Kaderisasi',
 'GMKI Cabang Padang kembali menerima puluhan kader baru dari berbagai perguruan tinggi di Kota Padang dalam rangkaian Masa Perkenalan Calon Anggota (Maperca) 2026.',
 '<p>Puji Tuhan, Gerakan Mahasiswa Kristen Indonesia (GMKI) Cabang Padang telah sukses menyelenggarakan <strong>Masa Perkenalan Calon Anggota (Maperca)</strong> Tahun 2026 di Student Center GMKI Padang.</p><p>Kegiatan ini dihadiri oleh puluhan mahasiswa Kristen dari Universitas Andalas (UNAND), Universitas Negeri Padang (UNP), Universitas Bung Hatta (UBH), UPI YPTK, dan berbagai kampus lainnya di Padang. Ketua Cabang GMKI Padang menyampaikan bahwa GMKI hadir untuk membentuk kader yang memiliki karakter Kristus, berpikir kritis, serta berjiwa melayani di tiga medan layan: Gereja, Perguruan Tinggi, dan Masyarakat.</p><p>Selamat datang rekan-rekan civitas baru di perarakan GMKI Cabang Padang. Ut Omnes Unum Sint!</p>',
 'BPC GMKI Padang',
 'published',
 142,
 NOW()),
(2,
 'Aksi Solidaritas Sosial dan Pelayanan Kasih GMKI Padang bagi Masyarakat',
 'aksi-solidaritas-sosial-pelayanan-kasih-gmki-padang',
 'Warta Cabang',
 'Bidang Aksi dan Pelayanan BPC GMKI Padang mengadakan bakti sosial dan pembagian paket sembako kepada masyarakat prasejahtera di sekitar Kota Padang.',
 '<p>Sebagai wujud nyata panca kegiatan berjuang dan bersosialisasi, GMKI Cabang Padang menyelenggarakan aksi sosial pelayanan kemanusiaan. Kegiatan ini melibatkan kader dari seluruh komisariat di Kota Padang.</p><p>Diharapkan aksi ini terus memupuk kepekaan sosial kader GMKI terhadap pergumulan masyarakat dan lingkungan sekitar.</p>',
 'BPC GMKI Padang',
 'published',
 89,
 NOW())
ON DUPLICATE KEY UPDATE `id` = `id`;

INSERT INTO `civitas` (`id`, `nim`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `telepon`, `email`, `perguruan_tinggi`, `fakultas`, `jurusan`, `komisariat`, `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`, `alamat_padang`) VALUES
(1, '2110532001', 'Josua Manik', 'L', 'Medan', '2003-05-14', '082199887766', 'josua.manik@student.unand.ac.id', 'Universitas Andalas', 'Ekonomi dan Bisnis', 'Manajemen', 'Komisariat UNAND', 2022, 'KTB', 'Aktif', 'Limau Manis, Kec. Pauh, Padang'),
(2, '22076045', 'Christin Natalia Simanjuntak', 'P', 'Pematangsiantar', '2004-12-25', '081377665544', 'christin.natalia@student.unp.ac.id', 'Universitas Negeri Padang', 'Bahasa dan Seni', 'Pendidikan Bahasa Inggris', 'Komisariat UNP', 2023, 'Maperca', 'Aktif', 'Air Tawar Barat, Padang Utara'),
(3, '201001321', 'Andreas Sibarani', 'L', 'Sibolga', '2002-08-19', '085211223344', 'andreas.sibarani@bunghatta.ac.id', 'Universitas Bung Hatta', 'Teknik Sipil dan Perencanaan', 'Teknik Sipil', 'Komisariat UBH', 2021, 'KK', 'Aktif', 'Ulak Karang Selatan, Padang')
ON DUPLICATE KEY UPDATE `id` = `id`;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
