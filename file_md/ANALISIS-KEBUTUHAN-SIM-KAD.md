# Analisis Kebutuhan Sistem
## Sistem Informasi dan Manajemen Keanggotaan Terintegrasi (SIM-KAD)
### GMKI Cabang Padang

**Versi:** 1.0  
**Tanggal:** 5 Oktober 2026  
**Status:** Draf analisis berbasis implementasi proyek; perlu validasi pemangku kepentingan  
**Tujuan dokumen:** Menjadi acuan kebutuhan, pengembangan, pengujian, dan penerimaan sistem.

> Dokumen ini disusun dari struktur kode, rute, konfigurasi, skema basis data, dan dokumentasi proyek yang tersedia. Keberadaan implementasi pada kode tidak otomatis berarti fitur sudah diuji atau disetujui sebagai kebutuhan final. Bagian “Perlu keputusan” harus dikonfirmasi oleh BPC GMKI Cabang Padang.

---

## 1. Ringkasan Eksekutif

SIM-KAD ditujukan sebagai portal informasi publik dan sistem internal untuk membantu GMKI Cabang Padang mengelola data civitas, memantau pendaftaran anggota baru, menerbitkan warta, mengelola profil dan struktur BPC, menyajikan statistik, serta mengawasi aktivitas dan integritas data.

Sistem memiliki tiga peran utama: Administrator Cabang, Operator Data & Warta, dan Pengawas. Pengunjung publik dapat melihat profil, struktur organisasi, berita, dan mengirim pesan melalui halaman kontak. Pengelolaan data dilakukan oleh pengguna yang sudah masuk dan dibatasi sesuai hak akses.

Pendaftaran anggota baru berbeda dari pengelolaan civitas yang sudah ditetapkan. Pada pendaftaran awal, komisariat, status keanggotaan komisariat, tahun Maperca, dan jenjang kaderisasi tidak boleh menjadi syarat. Sistem dapat menyimpan penanda proses internal untuk membedakan data pendaftaran dari civitas terdaftar; penanda tersebut bukan informasi yang harus diminta kepada pendaftar. Penempatan komisariat dan pencatatan kaderisasi dilakukan setelah informasi tersedia dan sebelum perubahan status ke civitas resmi, sesuai kebijakan organisasi yang perlu disahkan.

---

## 2. Latar Belakang dan Tujuan

### 2.1 Latar belakang

Kode proyek menunjukkan kebutuhan untuk menyatukan beberapa pekerjaan organisasi dalam satu sistem: publikasi informasi, pencatatan anggota, pengelolaan struktur dan profil cabang, laporan, serta pemantauan keamanan dan audit. Sistem juga membedakan calon/anggota baru dari data civitas yang telah dilengkapi informasi keanggotaan.

Alasan operasional yang lebih rinci—misalnya volume data, masalah proses manual, petugas yang terlibat, dan target layanan—belum terdokumentasi di kode dan harus dikonfirmasi melalui wawancara.

### 2.2 Tujuan sistem

1. Menyediakan portal informasi resmi GMKI Cabang Padang.
2. Menyediakan pencatatan terpusat dan dapat dicari untuk civitas dan anggota baru.
3. Mendukung tahapan pencatatan anggota baru tanpa memaksa data komisariat atau kaderisasi yang belum diketahui.
4. Membantu pengurus mengelola warta, profil, struktur, statistik, dan laporan.
5. Membatasi akses data sesuai tanggung jawab setiap peran.
6. Menyediakan jejak audit, kontrol keamanan, dan mekanisme pencadangan data.

### 2.3 Ukuran keberhasilan yang diusulkan

- Pengguna berwenang dapat menyelesaikan tugas utama melalui antarmuka web tanpa mengakses database secara langsung.
- Pendaftaran anggota baru dapat disimpan tanpa data komisariat dan kaderisasi.
- Data yang sudah lengkap dapat ditelusuri, difilter, dan diekspor oleh peran yang berwenang.
- Pengguna tanpa izin tidak dapat melakukan perubahan atau membuka data privat.
- Pemulihan dari cadangan diuji dan terdokumentasi sebelum sistem digunakan secara operasional.

Target angka (misalnya waktu respons, waktu pemulihan, dan tingkat ketersediaan) belum ditetapkan.

---

## 3. Ruang Lingkup

### 3.1 Termasuk dalam ruang lingkup

- Portal publik: beranda, profil/sejarah, struktur organisasi, katalog dan detail berita, serta kontak.
- Login, logout, ubah kata sandi, sesi, pembatasan percobaan login.
- Dashboard administrator dan dashboard pemantauan pengawas.
- Pencatatan, pencarian, filter, detail, pembaruan, dan ekspor data civitas.
- Pencatatan anggota baru dan proses pemindahan ke data civitas setelah kelengkapan yang diperlukan tersedia.
- Impor data civitas berbasis CSV; akses impor dibatasi kepada administrator.
- Pengelolaan warta dan publikasi.
- Pengelolaan profil organisasi serta struktur pengurus BPC.
- Statistik keanggotaan dan grafik.
- Audit log, pemantauan ancaman, pencadangan database, dan pemeriksaan manifest SHA-256.
- Pengelolaan foto anggota dan dokumen privat melalui penyimpanan terlindungi.

### 3.2 Tidak termasuk atau belum terkonfirmasi

- Pendaftaran mandiri anggota baru oleh publik: tidak ditemukan sebagai alur/rute publik pada implementasi yang ditinjau.
- Pembayaran iuran, kas organisasi, atau transaksi keuangan: perlu konfirmasi; jangan menyimpulkan dari tab/dashboard BENCAB.
- Aplikasi Android/iOS tersendiri.
- Integrasi dengan sistem kampus, sistem organisasi nasional, WhatsApp, atau email otomatis.
- Alur persetujuan berjenjang untuk setiap perubahan data.
- Pemulihan database satu klik dari halaman web: keberadaan fitur pembuatan dan unduh backup tidak sama dengan fitur restore.

---

## 4. Pemangku Kepentingan dan Peran

| Peran | Kebutuhan utama | Hak yang tampak pada implementasi |
|---|---|---|
| Pengunjung publik | Membaca profil, struktur, dan warta; menghubungi sekretariat | Akses baca portal publik dan kirim formulir kontak |
| Administrator Cabang (`admin`) | Kendali penuh data, akun, konfigurasi organisasi, dan keamanan | CRUD civitas/warta/organisasi, impor, ekspor, pengelolaan pengguna, audit, keamanan, dan backup |
| Operator Data & Warta (`operator`) | Memutakhirkan data anggota dan warta sehari-hari | Kelola civitas dan warta sesuai izin; tidak mengelola akun dan area keamanan admin |
| Pengawas (`pengawas`) | Memantau kondisi organisasi, civitas, berita, statistik, audit, ancaman, dan backup | Dashboard dan inspeksi data; implementasi saat ini juga mengizinkan create/update civitas pada rute tertentu sehingga sifat baca-saja harus diklarifikasi |
| Pengurus BPC terkait | Menggunakan informasi sesuai fungsi, misalnya KETCAB, SEKCAB, BENCAB, MPPC | Dipetakan ke tampilan/isi dashboard pengawas; belum terlihat sebagai sub-peran teknis terpisah |
| Pengelola infrastruktur | Menjaga web server, PHP, database, domain, backup, dan pemulihan | Peran operasional di luar matriks akun aplikasi; perlu ditetapkan |

**Catatan penting RBAC:** Dokumentasi proyek menyebut panel pengawas sebagai baca-saja, tetapi matriks izin dan rute saat ini mengizinkan peran `pengawas` membuat/memperbarui civitas dan memanggil aksi pelantikan. Keputusan kebijakan dan penyelarasan kode diperlukan sebelum produksi.

---

## 5. Proses Bisnis Utama

### 5.1 Informasi publik

1. Pengunjung membuka beranda, profil, struktur, atau katalog berita.
2. Pengunjung dapat melihat detail berita yang dipublikasikan.
3. Pengunjung mengirim pesan melalui halaman kontak.
4. Pengurus yang berwenang memperbarui informasi yang tampil di portal.

**Perlu verifikasi:** Perilaku formulir kontak saat ini memberikan konfirmasi kepada pengirim dan mencatat audit; mekanisme penerusan pesan ke sekretariat (email, daftar pesan masuk, atau kanal lain) belum tampak dari implementasi yang ditinjau.

### 5.2 Pendaftaran anggota baru dan pemutakhiran civitas

1. Petugas berwenang memasukkan data identitas dasar anggota baru.
2. Data dapat disimpan tanpa komisariat, status keanggotaan komisariat, tahun Maperca, atau jenjang kaderisasi yang belum diketahui.
3. Sistem menandai catatan sebagai proses/kelompok anggota baru secara internal.
4. Petugas melengkapi informasi penempatan dan proses organisasi setelah terkonfirmasi.
5. Perpindahan ke data civitas resmi hanya dilakukan setelah prasyarat organisasi ditentukan.
6. Sistem mencatat pelaku dan waktu tindakan.

**Ketentuan kebutuhan:** Jangan menampilkan atau mewajibkan pilihan kaderisasi dan komisariat pada form pendaftaran awal. Jangan menyamakan penanda internal proses dengan status/jenjang yang harus dipilih oleh anggota baru.

**Model data saat ini:** Kode menggunakan satu tabel `civitas` dengan penanda `tingkat_kaderisasi` (antara lain `Maperca`, `KTB`, `KK`, `Alumni`) untuk memisahkan kelompok data. Ini merupakan detail implementasi saat ini; pemisahan tabel anggota baru dan civitas resmi dapat dipertimbangkan jika kebutuhan operasional menuntut siklus hidup yang lebih kompleks.

**Perlu keputusan:** Tentukan kapan seseorang resmi menjadi anggota, apa prasyarat “pelantikan”, apakah tahun Maperca selalu wajib, cara pencatatan anggota yang bukan anggota komisariat, dan apakah status “anggota komisariat” harus dipisah dari nama komisariat.

### 5.3 Pengelolaan informasi dan organisasi

- Admin/operator mengelola draf dan publikasi berita berdasarkan izin.
- Admin mengelola profil organisasi dan struktur kepengurusan.
- Publik dan pengawas membaca informasi yang sudah disetujui/ditayangkan.
- Pembagian kewenangan final untuk membuat, menyunting, dan menerbitkan konten perlu dikonfirmasi.

### 5.4 Pengawasan, audit, dan backup

- Pengawas meninjau ringkasan, statistik, data, audit, keamanan, dan informasi backup.
- Sistem mencatat tindakan penting beserta pengguna, waktu, alamat IP, dan objek terkait.
- Admin membuat arsip database terkompresi dan memeriksa manifest integritas file.
- Prosedur penyimpanan di lokasi berbeda dan uji restore perlu ditetapkan.

---

## 6. Kebutuhan Fungsional

Prioritas: **P1** = wajib untuk rilis operasional; **P2** = penting; **P3** = penyempurnaan. Status di bawah adalah kebutuhan yang diturunkan dari cakupan sistem; status implementasi aktual harus diuji.

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---:|---|
| FR-01 | Sistem menyediakan beranda, profil organisasi, struktur BPC, daftar dan detail warta, serta halaman kontak publik. | P1 | Halaman publik dapat dibuka tanpa login; berita yang bukan berstatus publik tidak tampil. |
| FR-02 | Sistem mengautentikasi pengguna internal dan menyediakan logout serta ubah kata sandi. | P1 | Kredensial salah ditolak; kata sandi baru memenuhi kebijakan; logout mengakhiri sesi. |
| FR-03 | Sistem membatasi halaman dan tindakan berdasarkan peran/izin. | P1 | Uji akses langsung ke URL dan POST tanpa izin; server menolak, bukan hanya menyembunyikan tombol. |
| FR-04 | Admin/operator berwenang membuat, melihat, memperbarui, dan mencari data civitas. | P1 | Data wajib tervalidasi; NIM unik; filter dan detail sesuai hasil pencarian. |
| FR-05 | Petugas dapat membuat catatan pendaftaran anggota baru tanpa mengisi komisariat, keanggotaan komisariat, tahun Maperca, atau jenjang kaderisasi. | P1 | Form menyimpan identitas minimal; kolom yang belum diketahui tidak menggagalkan penyimpanan. |
| FR-06 | Sistem membedakan data pendaftaran awal dari civitas resmi dan tidak meminta anggota baru menentukan jenjang kaderisasi. | P1 | Penanda internal dikelola server; nilai yang dikirim pengguna tidak dapat mengubah proses internal secara tidak sah. |
| FR-07 | Petugas dapat melengkapi data anggota baru dan memindahkan/melantiknya ke civitas setelah prasyarat organisasi terpenuhi. | P1 | Sistem memblokir perpindahan saat prasyarat wajib belum ada dan menampilkan alasan yang jelas. |
| FR-08 | Sistem mendukung filter/paginasi civitas dan ekspor laporan CSV bagi peran berizin. | P2 | Ekspor menghormati izin dan filter; data kosong ditampilkan secara konsisten. |
| FR-09 | Administrator dapat mengimpor data anggota secara massal dengan laporan baris berhasil/gagal. | P2 | Baris tidak valid dilaporkan; format dan perilaku duplikasi didokumentasikan. |
| FR-10 | Pengguna berizin mengelola warta dan status publikasinya. | P1 | Draf tidak terlihat pada portal publik; berita terbit dapat dicari dan dibuka. |
| FR-11 | Admin mengelola profil organisasi dan struktur BPC; publik/pengawas melihat data yang diizinkan. | P2 | Perubahan yang disimpan tercermin pada halaman yang relevan. |
| FR-12 | Sistem menampilkan ringkasan dan grafik keanggotaan yang bersumber dari database. | P2 | Angka dan kategori grafik dapat ditelusuri/diuji terhadap kueri sumber. |
| FR-13 | Sistem menyediakan log audit untuk tindakan penting. | P1 | Aksi create/update/delete/import/backup yang ditentukan menghasilkan catatan dengan aktor dan waktu. |
| FR-14 | Sistem menyediakan pemantauan ancaman dan status keamanan bagi peran yang berwenang. | P2 | Hanya peran yang disetujui yang dapat membaca atau mengubah status mitigasi. |
| FR-15 | Admin dapat membuat dan mengunduh backup database serta manifest hash untuk integritas berkas. | P1 | Backup valid dapat dibuka/dipulihkan pada lingkungan uji; rotasi dan retensi berjalan sesuai kebijakan. |
| FR-16 | Foto dan dokumen sensitif tidak dapat diakses secara publik dan diperiksa izinnya saat diunduh. | P1 | Permintaan tanpa autentikasi/izin ditolak; upload tervalidasi tipe dan ukurannya. |
| FR-17 | Form publik kontak menerima pesan dan memberikan umpan balik yang jujur tentang hasil pengiriman. | P2 | Sistem tidak menyatakan pesan “terkirim” sebelum ada penyimpanan/penerusan yang terverifikasi. |
| FR-18 | Sistem menampilkan status kosong, validasi, kegagalan, dan keberhasilan dengan pesan yang dapat dipahami. | P2 | Kegagalan database/upload tidak dilaporkan sebagai sukses. |

---

## 7. Kebutuhan Data

Entitas/tabel yang tampak pada skema master mencakup:

| Entitas | Isi utama | Pertimbangan |
|---|---|---|
| `users` | Username, email, hash kata sandi, nama, role, status, waktu login | Username/email unik; jangan menyimpan kata sandi teks biasa. |
| `civitas` | Identitas, kampus, komisariat, tahun Maperca, jenjang, status, foto/KTA, catatan | Perlu aturan lifecycle anggota baru dan civitas resmi serta nilai kosong yang sah. |
| `berita` | Judul, slug, kategori, ringkasan, konten, sampul, status, views, tanggal publikasi | Tentukan alur draf, persetujuan, dan publikasi. |
| `profil_organisasi` | Sejarah, visi/misi, Tri Panji, Panca Kegiatan, kontak dan media sosial | Tetapkan pemilik konten dan periode pembaruan. |
| `struktur_organisasi` | Nama, jabatan, bidang, periode, urutan, foto, kontak, status aktif | Tentukan satuan periode dan aturan riwayat pergantian pengurus. |
| `audit_logs` | Aktor, role, aksi, entitas, IP, user agent, waktu | Tentukan retensi, pembatasan akses, dan perlindungan dari perubahan/penghapusan. |
| `security_threats` | Jenis ancaman, IP, payload, severity, status, waktu | Batasi paparan payload sensitif dan tetapkan prosedur penanganan. |
| `login_attempts` | IP, username, sukses/gagal, user agent, waktu | Tetapkan retensi dan mekanisme pembersihan berkala. |

### Aturan data minimum yang diusulkan

- NIM/identitas kampus: wajib jika kebijakan anggota mengharuskannya; pengecualian untuk anggota non-mahasiswa harus diputuskan.
- Nama lengkap dan jenis kelamin: saat ini tervalidasi pada form internal; pastikan kebutuhan dan kebijakan privasi.
- Perguruan tinggi: diwajibkan oleh alur data saat ini; tentukan nilai yang sah untuk alumni/non-mahasiswa.
- Komisariat dan tahun Maperca: dapat kosong selama pendaftaran awal; jangan dipalsukan dengan nilai default yang seolah-olah benar.
- Jenjang kaderisasi: dikelola oleh petugas/proses internal, bukan pilihan wajib anggota pada pendaftaran awal.
- File foto/KTA: tujuan, dasar pengumpulan, akses, retensi, serta penghapusan harus ditetapkan.
- Data yang tidak diketahui harus dibedakan dari “tidak berlaku” dan “belum diverifikasi” bila proses memerlukannya.

---

## 8. Kebutuhan Nonfungsional

Kebutuhan berikut menjadi baseline usulan; target angka harus disepakati sebelum uji penerimaan.

### 8.1 Keamanan

- Semua perubahan data memakai validasi server-side dan token CSRF.
- Query database menggunakan parameter binding/prepared statements.
- Kata sandi disimpan menggunakan hash kuat dan tidak pernah dikirim/log sebagai teks biasa.
- Terapkan hak akses pada server untuk setiap rute, data, file, ekspor, dan tindakan.
- Batasi percobaan login; konfigurasi proyek menyediakan batas awal 5 percobaan dan masa peluruhan 15 menit.
- File privat disimpan di luar web root atau dilindungi kuat dan hanya disajikan melalui controller berizin.
- Terapkan HTTPS, secret yang unik per lingkungan, `APP_DEBUG=false`, backup terenkripsi/terlindungi, pembaruan dependensi, dan audit akses pada produksi.
- Hindari menaruh kredensial contoh/default atau data sensitif dalam dokumentasi yang didistribusikan.

### 8.2 Privasi dan tata kelola

- Tetapkan tujuan dan dasar pemrosesan data pribadi, pemberitahuan privasi, masa retensi, hak koreksi, penghapusan, dan penanggung jawab data.
- Batasi data ekspor dan unduhan foto/KTA sesuai kebutuhan tugas.
- Catat siapa yang mengakses/mengekspor data sensitif jika kebijakan organisasi mengharuskan.

### 8.3 Kemudahan penggunaan dan aksesibilitas

- Antarmuka berbahasa Indonesia dan konsisten pada desktop serta perangkat seluler.
- Form menjelaskan mana data wajib, opsional, belum diketahui, dan siapa yang akan melihatnya.
- Gunakan label yang dapat diakses keyboard/pembaca layar, kontras memadai, dan pesan kesalahan dekat dengan field terkait.
- Hindari mewajibkan informasi organisasi yang memang belum dimiliki anggota baru.

### 8.4 Kinerja dan ketersediaan

- Tetapkan target waktu respons untuk halaman umum, daftar dengan filter, ekspor, dan dashboard setelah volume penggunaan diperkirakan.
- Gunakan paginasi; hindari memuat semua data anggota sekaligus.
- Tetapkan target availability, backup frequency, RPO, dan RTO sebelum produksi.
- Pantau kegagalan aplikasi, database, kapasitas file, sertifikat TLS, dan proses backup.

### 8.5 Kompatibilitas dan pemeliharaan

- Persyaratan proyek menyebut PHP 8.1+, PDO MySQL, mbstring, fileinfo, GD, zlib, dan MySQL/MariaDB.
- Server produksi perlu mengarahkan document root ke `frontend/public`, memblokir akses langsung ke file konfigurasi/penyimpanan privat, dan mengaktifkan rewrite bila Apache digunakan.
- Migrasi database harus diberi versi, diuji terhadap salinan database, dan memiliki rencana rollback/pemulihan.
- Sediakan lingkungan pengembangan, staging, dan produksi yang terpisah.

---

## 9. Arsitektur dan Batas Implementasi

- **Frontend:** template PHP dan aset CSS/JavaScript di `frontend/`; `frontend/public` menjadi web root.
- **Backend:** PHP native dengan pola MVC berlapis (routes, controllers, services, repositories).
- **Database:** MySQL/MariaDB melalui PDO.
- **Autentikasi/otorisasi:** sesi dan middleware/permission berbasis role.
- **Grafik:** Chart.js lokal.
- **Penyimpanan:** aset publik di area frontend; berkas sensitif di penyimpanan privat.
- **Deployment lokal:** PHP built-in server melalui `jalankan.bat`; database lokal melalui MoWeS sesuai README.
- **Integrasi eksternal yang tampak:** Google Fonts/Tailwind CDN pada beberapa halaman dan Ngrok untuk demo lokal; jangan mengandalkan tunnel demo sebagai hosting produksi.

---

## 10. Asumsi, Risiko, dan Keputusan yang Belum Selesai

| ID | Pertanyaan/risiko | Keputusan yang diperlukan |
|---|---|---|
| D-01 | Siapa yang boleh melihat, membuat, memperbarui, menghapus, dan melantik anggota? Kode dan dokumentasi tidak sepenuhnya selaras untuk pengawas. | Sahkan matriks role-per-action lalu sesuaikan route, controller, menu, dan pengujian. |
| D-02 | Apa definisi administratif anggota baru, calon anggota, Maperca, dan civitas resmi? | Sahkan nama status, transisi, pemilik keputusan, dan prasyarat transisi. |
| D-03 | Apakah NIM dan perguruan tinggi selalu wajib, termasuk anggota non-mahasiswa/alumni? | Tentukan field wajib dan mekanisme ID alternatif. |
| D-04 | Apakah pendaftaran harus tersedia untuk publik? | Saat ini tidak tampak rute/form pendaftaran publik; jika diperlukan, rancang verifikasi, anti-spam, consent, dan persetujuan. |
| D-05 | Bagaimana menyimpan status keanggotaan komisariat dan pemilihan komisariat? | Sahkan pilihan “anggota/bukan anggota/belum ditetapkan”, katalog komisariat, dan kebijakan anggota non-komisariat. |
| D-06 | Bagaimana pesan kontak benar-benar sampai ke sekretariat? | Pilih inbox internal, email, atau mekanisme lain; definisikan bukti pengiriman dan retensi pesan. |
| D-07 | Siapa yang dapat menyunting/menerbitkan berita? | Tetapkan alur draf, pemeriksaan, persetujuan, penerbitan, dan pengarsipan. |
| D-08 | Data sensitif apa yang perlu dikumpulkan dan berapa lama disimpan? | Sahkan pemberitahuan privasi, retensi, permintaan koreksi/penghapusan, dan petugas perlindungan data. |
| D-09 | Bagaimana backup dipulihkan dan diverifikasi? | Tetapkan RPO/RTO, lokasi salinan off-site, enkripsi, akses, jadwal, dan uji restore. |
| D-10 | Target pemakaian dan performa belum ada. | Perkirakan jumlah anggota, admin bersamaan, pertumbuhan file, serta target respons/availability. |
| D-11 | Akun awal dan akun contoh berisiko digunakan tanpa perubahan. | Ganti password default, pastikan hash/akun seed sesuai, dan jangan membuka sistem publik sebelum kredensial disetel. |
| D-12 | Form kontak saat ini belum menunjukkan mekanisme penerusan yang dapat diverifikasi. | Jangan mengklaim pesan terkirim sebelum disimpan atau diteruskan secara nyata. |

---

## 11. Kriteria Penerimaan Sistem

1. Setiap role diuji untuk akses yang diizinkan dan ditolak, termasuk akses langsung melalui URL dan request mutasi.
2. Pendaftaran anggota baru berhasil tanpa komisariat, status komisariat, tahun Maperca, dan jenjang kaderisasi.
3. Data anggota baru tidak tampil sebagai civitas resmi sampai transisi yang disahkan terjadi.
4. Transisi anggota baru ditolak dengan pesan yang jelas bila informasi/prasyarat yang ditetapkan belum lengkap.
5. Pencarian, filter, paginasi, impor, dan ekspor menghasilkan data yang konsisten dengan database.
6. Berita draf tidak dapat dibuka publik; berita terbit tersedia pada daftar dan halaman detail.
7. File foto/KTA dan file konfigurasi tidak dapat diakses langsung oleh pengunjung tanpa izin.
8. Validasi upload menolak MIME/ukuran yang tidak diizinkan.
9. Audit log mencatat tindakan kritis tanpa menyimpan kata sandi atau rahasia.
10. Backup dapat dibuat, integritasnya diperiksa, dan pemulihan diuji pada lingkungan non-produksi.
11. Halaman utama dan form utama dapat dipakai pada lebar layar ponsel dan desktop tanpa kehilangan fungsi.
12. Pesan sukses, gagal validasi, gagal database, dan gagal upload mencerminkan hasil sebenarnya.

---

## 12. Tahapan Implementasi yang Disarankan

1. **Validasi kebutuhan & tata kelola:** wawancara BPC, sahkan role, alur anggota, privasi, dan laporan.
2. **Fondasi & data:** finalkan skema/migrasi, data wajib/opsional, seed akun aman, lingkungan dan backup.
3. **Akses & keamanan:** autentikasi, RBAC konsisten, CSRF, sesi, audit, validasi upload, proteksi data.
4. **Operasional inti:** civitas, pendaftaran anggota baru, pelengkapan data dan transisi, filter dan impor/ekspor.
5. **Portal & publikasi:** profil, struktur, warta, kontak dan proses persetujuan konten.
6. **Dashboard & analitik:** metrik yang definisinya disepakati, grafik, audit/monitoring.
7. **Uji penerimaan & kesiapan produksi:** uji alur end-to-end, aksesibilitas, keamanan, performa, restore backup, dokumentasi dan pelatihan.

Urutan ini adalah rekomendasi perencanaan; penjadwalan dan prioritas akhir perlu disepakati.

---

## 13. Rencana Pengujian Minimum

- **Unit/integrasi:** validasi, layanan bisnis, repository, transisi anggota, filter, impor/ekspor.
- **End-to-end:** login tiap role, CRUD, pendaftaran awal tanpa komisariat/kaderisasi, pelengkapan dan pelantikan, publikasi warta.
- **Otorisasi negatif:** operator/pengawas mencoba aksi yang tidak diizinkan; pengguna anonim meminta data internal/file privat.
- **Keamanan aplikasi:** CSRF, XSS tersimpan/refleksi, SQL injection melalui input, pembatasan login, upload berbahaya, kebocoran debug/secret.
- **Data dan backup:** migrasi database kosong dan database berjalan, ekspor, backup, verifikasi hash, restore pada database uji.
- **UI/responsif:** desktop, tablet, ponsel, navigasi keyboard, pesan validasi, tabel lebar, dan tampilan kosong.
- **Operasional:** startup/shutdown server, konektivitas MySQL, log error, izin direktori, serta instruksi pemulihan.

---

## 14. Glosarium

- **BPC:** Badan Pengurus Cabang.
- **Civitas:** Catatan data anggota/civitas yang dikelola sistem; definisi resmi organisasi perlu disahkan.
- **KETCAB:** Ketua Cabang.
- **SEKCAB:** Sekretaris Cabang.
- **BENCAB:** Bendahara Cabang.
- **MPPC:** Majelis Pertimbangan/Pengawas Cabang.
- **Maperca:** Masa Perkenalan Calon Anggota.
- **KTB:** Kelompok Tumbuh Bersama.
- **KTA:** Kartu Tanda Anggota.
- **RBAC:** Role-Based Access Control, pembatasan akses berdasarkan peran.
- **RPO/RTO:** Batas kehilangan data yang dapat diterima / target waktu pemulihan layanan.

---

## 15. Sumber Analisis di Proyek

Dokumen ini terutama diturunkan dari:

- `README.md` — gambaran sistem, persyaratan lingkungan, dan operasi.
- `backend/routes/public.php`, `backend/routes/auth.php`, `backend/routes/admin.php`, `backend/routes/pengawas.php` — rute publik dan internal.
- `backend/config/roles.php` — izin peran.
- `backend/controllers/` dan `backend/services/` — alur bisnis.
- `backend/database/gmki_padang.sql` dan `backend/database/migrations/` — struktur data.
- `frontend/templates/` dan `frontend/public/assets/` — layar dan aset pengguna.
- `composer.json` — versi minimum PHP dan dependensi.

**Pernyataan akhir:** Dokumen ini adalah baseline analisis sistem, bukan bukti bahwa semua kebutuhan telah disetujui atau seluruh fitur telah lolos pengujian. Pemilik proses perlu meninjau terutama bagian pendaftaran/transisi anggota, hak pengawas, privasi data, dan pemulihan backup sebelum rilis produksi.
