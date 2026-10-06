-- =====================================================================
-- Seeds: admin.sql
-- Akun Awal, Profil Organisasi, & Data Awal GMKI Cabang Padang
-- =====================================================================

-- Akun Default Administrator & Pengawas
-- Password admin: Admin@GMKI2026! (Hash bcrypt & SHA-256 kompatibel)
-- Password pengawas: Pengawas@2026!
-- Password operator: Operator@2026!

INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `status`, `created_at`) VALUES
(1, 'MacTavish0987', 'MacTavish1604@gmail.com', '$2y$10$B9Z2qPig2p54gMm/hpvx9uU0b9AjfPnDfqcAol/t9czJO6O3dG3NC', 'Administrator BPC GMKI Padang', 'admin', 'aktif', NOW()),
(2, 'Dani_Manik1945', 'pengawas@gmkicabangpadang.or.id', '$2y$10$y6U9qyb8xH64ePwxvC4OA.LQhwqK4G2Xt5C8RQ.CzQk8nwAQy8otq', 'Ketua Cabang', 'pengawas', 'aktif', NOW()),
(3, 'Dan_PP12', 'operator@gmkicabangpadang.or.id', '$2y$10$Sjm0.sP8sPbarxdXqXP4Vu4ILvxSqGZobKECyEMqUKsbJ4hVXMx/2', 'Sekretaris Cabang', 'pengawas', 'aktif', NOW())
ON DUPLICATE KEY UPDATE 
    `username` = VALUES(`username`),
    `email` = VALUES(`email`),
    `password` = VALUES(`password`),
    `nama_lengkap` = VALUES(`nama_lengkap`);

-- Data Profil GMKI Cabang Padang
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
ON DUPLICATE KEY UPDATE `nama_organisasi` = VALUES(`nama_organisasi`);

-- Struktur Kepengurusan BPC Awal
INSERT INTO `struktur_organisasi` (`nama`, `jabatan`, `bidang`, `periode`, `urutan`, `telepon`, `status_aktif`) VALUES
('Yeremia Pratama, S.T.', 'Ketua Cabang', 'BPC (Badan Pengurus Cabang)', '2024-2026', 1, '081234567891', 1),
('Debora Silalahi, S.Ked.', 'Sekretaris Cabang', 'BPC (Badan Pengurus Cabang)', '2024-2026', 2, '081234567892', 1),
('Samuel Tampubolon, S.E.', 'Bendahara Cabang', 'BPC (Badan Pengurus Cabang)', '2024-2026', 3, '081234567893', 1),
('Grace Novita Hutapea', 'KABID OR', 'BPC (Badan Pengurus Cabang)', '2024-2026', 4, '081234567894', 1),
('Daniel Kristianto', 'KABID PKK', 'BPC (Badan Pengurus Cabang)', '2024-2026', 5, '081234567895', 1),
('Ruth Marbun', 'KABID AKSPEL', 'BPC (Badan Pengurus Cabang)', '2024-2026', 6, '081234567896', 1);

-- Data Berita Awal
INSERT INTO `berita` (`judul`, `slug`, `kategori`, `ringkasan`, `konten`, `penulis_nama`, `status`, `views`, `published_at`) VALUES
('Masa Perkenalan Calon Anggota (Maperca) GMKI Padang Tahun 2026 Sukses Digelar',
 'maperca-gmki-padang-tahun-2026-sukses-digelar',
 'Kaderisasi',
 'GMKI Cabang Padang kembali menerima puluhan kader baru dari berbagai perguruan tinggi di Kota Padang dalam rangkaian Masa Perkenalan Calon Anggota (Maperca) 2026.',
 '<p>Puji Tuhan, Gerakan Mahasiswa Kristen Indonesia (GMKI) Cabang Padang telah sukses menyelenggarakan <strong>Masa Perkenalan Calon Anggota (Maperca)</strong> Tahun 2026 di Student Center GMKI Padang.</p><p>Kegiatan ini dihadiri oleh puluhan mahasiswa Kristen dari Universitas Andalas (UNAND), Universitas Negeri Padang (UNP), Universitas Bung Hatta (UBH), UPI YPTK, dan berbagai kampus lainnya di Padang. Ketua Cabang GMKI Padang menyampaikan bahwa GMKI hadir untuk membentuk kader yang memiliki karakter Kristus, berpikir kritis, serta berjiwa melayani di tiga medan layan: Gereja, Perguruan Tinggi, dan Masyarakat.</p><p>Selamat datang rekan-rekan civitas baru di perarakan GMKI Cabang Padang. Ut Omnes Unum Sint!</p>',
 'BPC GMKI Padang',
 'published',
 142,
 NOW()),
('Aksi Solidaritas Sosial dan Pelayanan Kasih GMKI Padang bagi Masyarakat',
 'aksi-solidaritas-sosial-pelayanan-kasih-gmki-padang',
 'Warta Cabang',
 'Bidang Aksi dan Pelayanan BPC GMKI Padang mengadakan bakti sosial dan pembagian paket sembako kepada masyarakat prasejahtera di sekitar Kota Padang.',
 '<p>Sebagai wujud nyata panca kegiatan berjuang dan bersosialisasi, GMKI Cabang Padang menyelenggarakan aksi sosial pelayanan kemanusiaan. Kegiatan ini melibatkan kader dari seluruh komisariat di Kota Padang.</p><p>Diharapkan aksi ini terus memupuk kepekaan sosial kader GMKI terhadap pergumulan masyarakat dan lingkungan sekitar.</p>',
 'BPC GMKI Padang',
 'published',
 89,
 NOW());

-- Data Contoh Civitas
INSERT INTO `civitas` (`nim`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `telepon`, `email`, `perguruan_tinggi`, `fakultas`, `jurusan`, `komisariat`, `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`, `alamat_padang`) VALUES
('2110532001', 'Josua Manik', 'L', 'Medan', '2003-05-14', '082199887766', 'josua.manik@student.unand.ac.id', 'Universitas Andalas', 'Ekonomi dan Bisnis', 'Manajemen', 'Komisariat UNAND', 2022, 'KTB', 'Aktif', 'Limau Manis, Kec. Pauh, Padang'),
('22076045', 'Christin Natalia Simanjuntak', 'P', 'Pematangsiantar', '2004-12-25', '081377665544', 'christin.natalia@student.unp.ac.id', 'Universitas Negeri Padang', 'Bahasa dan Seni', 'Pendidikan Bahasa Inggris', 'Komisariat UNP', 2023, 'Maperca', 'Aktif', 'Air Tawar Barat, Padang Utara'),
('201001321', 'Andreas Sibarani', 'L', 'Sibolga', '2002-08-19', '085211223344', 'andreas.sibarani@bunghatta.ac.id', 'Universitas Bung Hatta', 'Teknik Sipil dan Perencanaan', 'Teknik Sipil', 'Komisariat UBH', 2021, 'KK', 'Aktif', 'Ulak Karang Selatan, Padang');
