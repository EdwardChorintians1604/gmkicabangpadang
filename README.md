# Sistem Informasi & Manajemen Keanggotaan Terintegrasi (SIM-KAD)
## GMKI Cabang Padang (Gerakan Mahasiswa Kristen Indonesia)

Sistem Informasi dan Manajemen Keanggotaan Terintegrasi GMKI Cabang Padang dirancang khusus untuk memfasilitasi pendataan civitas, jenjang kaderisasi, publikasi warta cabang, pengelolaan kepengurusan BPC, serta pemantauan eksekutif dan audit keamanan secara masif dan aman.

Aplikasi ini dibangun menggunakan arsitektur **Clean MVC Native PHP (PSR-4)** tanpa ketergantungan framework berat. Hak akses BPC dipisahkan berdasarkan tugas: Ketcab untuk pengawasan dan koordinasi, Sekcab untuk administrasi, Bencab untuk laporan keuangan miliknya, serta Sekfung Medko untuk konten. Keempatnya memakai sidebar role-specific yang sama pada setiap panel dan memiliki chat koordinasi antarperan. Administrator sistem tetap terpisah dari peran Ketcab.

---

## 🏛️ Struktur Folder & Berkas Proyek

```text
gmki-padang/
├── .htaccess                         Arahkan semua akses web ke frontend/public/
├── index.php                         Cadangan aman: meneruskan ke frontend/public/index.php
│
├── frontend/                         ===== TAMPILAN (Yang dilihat pengguna)
│   ├── public/                       Satu-satunya folder yang boleh dibuka oleh browser
│   │   ├── index.php                 Pintu masuk utama semua halaman (Front Controller)
│   │   ├── .htaccess                 Konfigurasi rewrite Apache & Security Headers
│   │   ├── robots.txt                Aturan perayap mesin pencari (SEO)
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   │   ├── app.css           Design tokens dasar, tipografi, dan utilitas flex/grid
│   │   │   │   ├── public.css        Gaya antarmuka portal publik dan kartu warta
│   │   │   │   ├── admin.css         Gaya panel kendali admin, formulir, dan tabel data
│   │   │   │   └── pengawas.css      Gaya panel pengawas, tab eksekutif, dan status baca-saja
│   │   │   ├── js/
│   │   │   │   ├── app.js            Navigasi responsif portal publik & penanganan notifikasi
│   │   │   │   ├── validation.js     Validasi formulir sisi klien secara real-time
│   │   │   │   ├── admin.js          Pengalih tab dashboard pengawas, drawer sidebar, & preview gambar
│   │   │   │   └── statistik.js      Inisialisasi grafik visual interaktif Chart.js
│   │   │   ├── vendor/
│   │   │   │   └── chart.umd.min.js  Pustaka Chart.js mandiri offline (tanpa ketergantungan CDN)
│   │   │   └── images/
│   │   │       ├── logo-gmki.png     Logo resmi GMKI resolusi tajam
│   │   │       ├── favicon.png       Ikon tab browser resmi
│   │   │       └── default-profile.png Foto avatar cadangan profil civitas
│   │   └── uploads/                  Berkas publik yang dapat dilihat pengunjung
│   │       ├── berita/               Gambar sampul artikel dan warta cabang
│   │       ├── lampiran/             Lampiran dokumen publik dan press release
│   │       └── organisasi/           Foto resmi fungsionaris Badan Pengurus Cabang
│   └── templates/                    Halaman HTML+PHP murni tanpa logika query basis data
│       ├── layouts/
│       │   ├── public.php            Kerangka tata letak portal publik (navbar + footer)
│       │   ├── admin.php             Kerangka kerja panel administrator (sidebar + breadcrumb)
│       │   ├── pengawas.php          Kerangka kerja panel pengawas (sidebar amber + status baca-saja)
│       │   └── flash-message.php     Komponen render pesan notifikasi flash (sukses/gagal/peringatan)
│       ├── partials/                 Komponen antarmuka yang dipakai bersama
│       │   ├── stat-card.php         Kartu metrik statistik ringkasan
│       │   ├── tabel.php             Komponen tabel responsif serbaguna
│       │   ├── pagination.php        Navigasi pemecah halaman (prev/pages/next)
│       │   └── chart-card.php        Kartu pembungkus kanvas grafik Chart.js
│       ├── errors/
│       │   ├── 403.php               Halaman larangan akses (Akses Ditolak / Forbidden)
│       │   ├── 404.php               Halaman berkas/rute tidak ditemukan
│       │   └── 500.php               Halaman penanganan kesalahan server internal
│       ├── auth/
│       │   ├── login.php             Pintu masuk otentikasi tunggal untuk seluruh tingkatan pengguna
│       │   └── ubah-password.php     Formulir mandiri pembaruan kata sandi pengguna
│       ├── public/                   Halaman Pengunjung Publik
│       │   ├── home.php              Beranda portal, sorotan warta, dan sambutan pengurus
│       │   ├── profil.php            Sejarah GMKI Padang, Tri Panji, Panca Kegiatan, Visi & Misi
│       │   ├── struktur-organisasi.php Bagan pengurus BPC aktif menurut bidang pelayanan
│       │   ├── berita.php            Katalog publikasi berita dan filter kategori artikel
│       │   ├── detail-berita.php     Halaman baca berita lengkap dan pencatatan views
│       │   └── kontak.php            Informasi alamat sekretariat, peta, dan formulir pesan publik
│       ├── admin/                    Panel Kendali Pengelolaan Penuh (Admin & Operator)
│       │   ├── dashboard.php         Pusat ringkasan statistik, aktivitas terkini, dan pintasan modul
│       │   ├── civitas/              Modul keanggotaan: index.php, form.php, detail.php, impor.php
│       │   ├── berita/               Modul warta berita: index.php, form.php
│       │   ├── organisasi/           Pengaturan cabang: profil.php, struktur.php
│       │   ├── statistik/            Pusat analisis grafis demografi: index.php
│       │   ├── akun/                 Manajemen pengguna sistem: index.php, form.php
│       │   └── keamanan/             Pusat keamanan: audit-log.php, ancaman.php, backup.php
│       └── pengawas/                 Panel Pantauan Baca-Saja (KETCAB, SEKCAB, BENCAB, MPPC)
│           ├── dashboard/
│           │   ├── index.php         Kontainer utama dashboard pengawas dan tab selector
│           │   ├── _ketcab.php       Tab pemantauan kebijakan, kaderisasi, & pimpinan cabang
│           │   ├── _sekcab.php       Tab pemantauan administrasi, warta, & keaktifan komisariat
│           │   └── _bencab.php       Tab pemantauan inventarisasi aset & iuran civitas
│           ├── civitas/              index.php (daftar pantau), detail.php (detail profil & KTA)
│           ├── berita/               index.php (tinjau warta), detail.php (isi warta lengkap)
│           ├── organisasi/           profil.php (tinjau visi-misi), struktur.php (tinjau fungsionaris)
│           ├── statistik/            index.php (analisis grafis Chart.js baca-saja)
│           └── pemantauan/           audit-log.php (jejak audit), keamanan.php, backup.php
│
├── backend/                          ===== LOGIKA & DATA (Tersembunyi dari publik)
│   ├── config/                       Konfigurasi Sentral
│   │   ├── app.php                   Pengaturan nama aplikasi, URL dasar, lingkungan, & timezone
│   │   ├── database.php              Konfigurasi koneksi MySQL/MariaDB (host, port, user, pass, db)
│   │   ├── roles.php                 Pemetaan hak akses (RBAC) dan wewenang tiap peran
│   │   ├── security.php              Pengaturan proteksi brute force, durasi kunci, & batasan sesi
│   │   └── storage.php               Daftar path direktori penyimpanan publik dan privat
│   ├── routes/                       Perutean Modular Bersih
│   │   ├── public.php                Rute portal umum pengunjung (/, /profil, /berita, dll.)
│   │   ├── auth.php                  Rute autentikasi (/login, /logout, /ubah-password)
│   │   ├── admin.php                 Rute operasional CRUD (/admin/*) dengan proteksi peran
│   │   ├── pengawas.php              Rute pemantauan baca-saja (/pengawas/*)
│   │   └── workspace.php             Rute ruang kerja anggota BPC
│   ├── core/                         Fondasi Arsitektur Sistem
│   │   ├── Autoloader.php            Pemuat otomatis kelas (PSR-4 Autoloader) multi-platform
│   │   ├── Router.php                Pemeriksa URL HTTP dan dispatching controller & middleware
│   │   ├── Request.php               Enkapsulasi masukan HTTP (GET, POST, headers, upload berkas, IP)
│   │   ├── Response.php              Format respons server (HTML, JSON, pengalihan URL, unduh berkas)
│   │   ├── View.php                  Mesin render tampilan template dengan injeksi data aman
│   │   ├── Session.php               Manajemen sesi terenkripsi dengan penanganan data flash
│   │   ├── Database.php              Koneksi tunggal PDO MySQL dengan mode unbuffered dan prepared statement
│   │   ├── Auth.php                  Manajemen status login pengguna dan penyimpanan sesi kredensial
│   │   ├── Authorization.php         Pemeriksa wewenang aksi (Gate / Permission Checker)
│   │   ├── Csrf.php                  Pembuat dan pemverifikasi token kriptografis anti-CSRF
│   │   ├── Validator.php             Mesin validasi aturan formulir (required, min, max, email, dll.)
│   │   └── Hash.php                  Penyandi kata sandi berbasis Bcrypt standar industri
│   ├── middleware/                   Penyaring Permintaan HTTP
│   │   ├── Middleware.php            Antarmuka (Interface) kontrak middleware
│   │   ├── Auth.php                  Pencegah akses bagi pengguna yang belum terotentikasi
│   │   ├── Guest.php                 Pencegah pengguna login mengakses kembali formulir login
│   │   ├── Role.php                  Pembatas akses berdasarkan peran (admin, pengawas, operator)
│   │   └── Csrf.php                  Validasi wajib token CSRF pada seluruh permintaan mutasi data
│   ├── helpers/                      Fungsi Bantuan Global
│   │   ├── functions.php             Fungsi format tanggal Indonesia, sanitasi output (e), asset, & ikon SVG
│   │   ├── security.php              Fungsi sanitasi nama berkas, deteksi ancaman, & proteksi XSS
│   │   └── upload.php                Validasi upload berkas aman berbasis MIME tipe asli (finfo)
│   ├── controllers/                  Pengendali Alur Logika
│   │   ├── PublicController.php      Pengendali rute portal publik dan formulir kontak
│   │   ├── AuthController.php        Pengendali login, logout, dan penggantian kata sandi mandiri
│   │   ├── WorkspaceController.php  Pengendali inventaris, arsip, laporan, strategi, koordinasi
│   │   ├── FileController.php        Pengendali akses berkas privat (foto anggota & KTA terenkripsi)
│   │   ├── Admin/                    Pengendali Operasional Penuh (CRUD & Mutasi Data)
│   │   │   ├── DashboardController.php   Metrik admin, ringkasan aktivitas, & kesehatan sistem
│   │   │   ├── CivitasController.php     Pengelolaan data anggota, impor CSV, ekspor, & KTA
│   │   │   ├── NewsController.php        Pengelolaan warta cabang, unggah sampul, & publikasi
│   │   │   ├── OrganizationController.php Pembaruan profil cabang & struktur fungsionaris BPC
│   │   │   ├── StatisticsController.php  Analisis statistik civitas dan sebaran komisariat
│   │   │   ├── UserController.php        Pengelolaan akun pengguna dan penugasan peran
│   │   │   ├── SecurityController.php    Pemantauan log ancaman & tindakan blokir IP
│   │   │   └── BackupController.php      Pemicu pencadangan basis data & pembuatan manifest SHA-256
│   │   └── Pengawas/                 Pengendali Pemantauan Baca-Saja (Executive Oversight)
│   │       ├── DashboardController.php   Dashboard eksekutif dengan tab KETCAB, SEKCAB, BENCAB
│   │       ├── CivitasController.php     Peninjauan basis data kader dan ekspor CSV laporan
│   │       ├── NewsController.php        Peninjauan warta cabang dan pratinjau publikasi
│   │       ├── OrganizationController.php Peninjauan profil dan bagan fungsionaris BPC
│   │       ├── StatisticsController.php  Analisis visual demografi anggota (Chart.js)
│   │       └── MonitoringController.php  Inspeksi jejak audit, ancaman keamanan, & integritas cadangan
│   ├── services/                     Aturan Bisnis Terpusat
│   │   ├── AuthService.php           Otentikasi kredensial, proteksi brute force, & pencatatan log masuk
│   │   ├── UserService.php           Pembuatan, pembaruan, dan pengaturan status pengguna
│   │   ├── CivitasService.php        Logika validasi anggota, filter multi-kriteria, & pengolahan CSV
│   │   ├── NewsService.php           Pembangkit slug unik, pengelolaan berkas gambar, & penghitungan views
│   │   ├── OrganizationService.php   Penyimpanan profil cabang & urutan fungsionaris BPC
│   │   ├── StatisticsService.php     Kompilasi agregasi data untuk visualisasi Chart.js
│   │   ├── StorageService.php        Penyimpanan berkas privat dan pemeriksaan izin unduh
│   │   ├── AuditLogService.php       Pencatatan jejak audit kronologis setiap mutasi data
│   │   ├── SecurityService.php       Deteksi anomali permintaan, audit keamanan, & pencatatan IP
│   │   └── BackupService.php         Pencadangan database terkompresi (.sql.gz) & manifest SHA-256
│   ├── repositories/                 Akses Basis Data SQL Murni (PDO Prepared Statements)
│   │   ├── UserRepository.php        Query pengelolaan tabel `users`
│   │   ├── CivitasRepository.php     Query pengelolaan tabel `civitas`
│   │   ├── NewsRepository.php        Query pengelolaan tabel `berita`
│   │   ├── OrganizationRepository.php Query pengelolaan tabel `profil_organisasi` & `struktur_organisasi`
│   │   ├── StatisticsRepository.php  Query agregasi COUNT, GROUP BY untuk metrik statistik
│   │   ├── AuditLogRepository.php    Query pengelolaan tabel `audit_logs`
│   │   └── SecurityRepository.php    Query pengelolaan tabel `security_threats`
│   └── database/                     Skema Basis Data & Benih Awal
│       ├── gmki_padang.sql           Skema lengkap seluruh tabel (kompatibel MySQL 5.5 s/d 8.x)
│       ├── seeds/admin.sql           Data awal akun default, profil cabang, dan fungsionaris BPC
│       └── migrations/               Struktur migrasi terpisah untuk pengembangan bertahap
│           ├── 006_allow_unassigned_new_members.sql
│           │                          Mengizinkan pendaftaran awal tanpa komisariat/tahun Maperca
│           └── 007_add_commissariat_membership_status.sql
│                                      Mencatat status anggota/tidak anggota komisariat
│
├── storage/                          ===== DATA PRIBADI & CADANGAN (Tertutup Total)
│   ├── private/
│   │   ├── foto-anggota/             Foto identitas anggota (hanya dapat diakses melalui otentikasi)
│   │   └── kta/                      Hasil desain Canva KTA per anggota yang diunggah
│   ├── backup/
│   │   ├── database/                 Arsip dump basis data (.sql.gz) dengan rotasi 3 berkas terbaru
│   │   └── uploads/                  `manifest.csv`: daftar seluruh berkas terunggah beserta hash SHA-256
│   ├── logs/
│   │   └── app.log                   Catatan kesalahan sistem dan galat aplikasi
│   └── temp/                         Folder pemrosesan berkas sementara saat proses impor/ekspor
│
├── vendor/                           Dependensi Composer (terpasang lokal)
├── .env.example                      Contoh konfigurasi variabel lingkungan untuk deployment
├── .env                              Konfigurasi lingkungan aktif
├── .gitignore                        Daftar berkas sensitif yang dikecualikan dari Git
├── composer.json                     Konfigurasi paket & autoload PSR-4
├── composer.lock                     Kunci versi paket Composer
├── jalankan.bat                      Pintasan satu-klik menjalankan server lokal PHP 8.2
└── README.md                         Dokumentasi arsitektur dan panduan operasional proyek
```

---

## 👥 Hak Akses & Matriks Otorisasi Pengguna (RBAC)

Sistem menggunakan model otorisasi berbasis peran (Role-Based Access Control) yang terbagi menjadi tiga kelompok utama:

| Modul / Fitur | Administrator (`admin`) | Operator (`operator`) | Pengawas (`pengawas` - KETCAB/SEKCAB/BENCAB/MPPC) |
|---|:---:|:---:|:---:|
| **Dashboard** | Full Metrics & Control | Operational Summary | Executive Oversight (`_ketcab`, `_sekcab`, `_bencab`) |
| **Data Civitas** | Tambah, Edit, Hapus, Impor, Ekspor | Tambah, Edit, Ekspor | Baca-Saja (Detail, Filter, Unduh CSV) |
| **KTA & Foto Privat** | Unggah & Kelola | Unggah & Kelola | Pratinjau KTA Digital (Baca-Saja) |
| **Warta & Publikasi** | Tulis, Edit, Terbitkan, Hapus | Tulis, Edit Draft | Tinjau Warta (Baca-Saja) |
| **Profil & Struktur BPC** | Kelola & Ubah Fungsionaris | Baca-Saja | Pantau Profil & Bagan BPC (Baca-Saja) |
| **Statistik & Chart.js** | Visualisasi Lengkap | Visualisasi Lengkap | Analitik Grafis Eksekutif |
| **Manajemen Akun** | Buat, Nonaktifkan, Reset | Ditolak (403) | Ditolak (403) |
| **Audit Trail (Log)** | Lihat & Filter | Ditolak (403) | Inspeksi Pengawasan Penuh |
| **Keamanan & Ancaman** | Mitigasi & Blokir IP | Ditolak (403) | Pemantauan Kesehatan Siber |
| **Cadangan Data & SHA-256** | Buat Dump & Perbarui Manifest | Ditolak (403) | Pemeriksaan Integritas Arsip |

---

## Ruang Kerja BPC dan Penerapan Role

- **Ketcab (`ketcab`)**: pantau anggota, konten, statistik, dan laporan; unduh laporan keuangan; catat strategi; kirim koordinasi/request. Role ini bukan administrator sistem.
- **Sekcab (`sekcab`)**: CRUD data anggota, inventaris, dan arsip surat privat.
- **Bencab (`bencab`)**: CRUD laporan dan lampiran keuangan milik akunnya sendiri. Ketcab mendapat akses baca/unduh.
- **Sekfung Medko (`sekfung_medko`)**: kelola konten berita dan gunakan ruang koordinasi.
- Arsip dan laporan disimpan di `storage/private/documents`, bukan direktori publik. Unggahan maksimal 10 MB, diperiksa MIME, dan hanya dapat diunduh setelah autentikasi serta pemeriksaan role/kepemilikan.
- Ruang koordinasi: `/ketcab/koordinasi` untuk Ketcab dan `/ruang-kerja/koordinasi` bagi anggota BPC lain.

### Aktivasi database

Untuk basis data yang sudah ada, buat backup terlebih dahulu lalu jalankan **sekali** `backend/database/migrations/012_create_officer_workspaces.sql`. Migrasi ini menambahkan role dan tabel modul, serta memetakan username Ketua dan Sekretaris Cabang yang sudah ada. Untuk instalasi baru, impor skema dan seed, lalu jalankan migrasi 012 sebelum membuka modul.

Bencab dapat dibuat administrator melalui `/admin/akun/create` dengan role **Bendahara Cabang** setelah migrasi. Kredensial awal diberikan secara privat dan tidak disimpan dalam source code atau dokumentasi.

---

## 📊 Integrasi Grafik Visual: Chart.js

Sistem telah dilengkapi dengan pustaka **Chart.js** versi UMD (`frontend/public/assets/vendor/chart.umd.min.js`) yang berjalan secara **offline** tanpa memerlukan koneksi internet.

Grafik yang tersedia secara otomatis di halaman `/admin/statistik` dan `/pengawas/statistik`:
1. **Civitas per Komisariat Kampus (Bar Chart):** Memetakan persebaran kader di basis kampus Kota Padang.
2. **Komposisi Rasio Gender (Doughnut Chart):** Memantau perbandingan anggota laki-laki dan perempuan.
3. **Tren Kaderisasi Maperca (Line Chart):** Melihat kurva penerimaan mahasiswa baru per tahun masa penerimaan anggota (Maperca).
4. **Sebaran Perguruan Tinggi (Horizontal Bar Chart):** Menampilkan konsentrasi mahasiswa di UNAND, UNP, UPI YPTK, UBH, dll.

---

## 📦 Pustaka & Dependensi Tambahan (Composer)

Proyek ini telah dikonfigurasi pada `composer.json` dengan pustaka-pustaka pilihan untuk mendukung performa dan keandalan saat peluncuran:

1. **`vlucas/phpdotenv`**: Manajemen variabel lingkungan standar industri (`.env`).
2. **`shuchkin/simplexlsx`**: Pustaka ringan untuk pembacaan dan impor berkas Excel (XLSX) massal tanpa membebani memori server.
3. **`dompdf/dompdf`**: Mesin konversi HTML ke PDF untuk mencetak Kartu Tanda Anggota (KTA) digital dan sertifikat kelulusan LK.
4. **`chart.js`**: Visualisasi data analitik demografi civitas secara interaktif.
5. **Lucide / Feather SVG Engine**: Sistem ikon vektor bawaan mandiri via helper `svg_icon()` dengan performa tinggi tanpa font fontawesome eksternal.

---

---

## ⚡ Panduan Menjalankan Sistem Secara Lokal

### Menggunakan MoWeS Portable + PHP 8.2 (Direkomendasikan)
1. Aktifkan **MoWeS Portable** (`E:\WebProgBPNama\mowes.exe`) agar layanan basis data MySQL aktif di port `3306`. Apache bawaan MoWeS boleh tetap berjalan di port `80`.
2. Jika database proyek sudah pernah dibuat, pastikan migrasi anggota sebelumnya telah diterapkan, lalu backup dan jalankan `backend/database/migrations/012_create_officer_workspaces.sql` melalui phpMyAdmin. Skema baru juga harus menjalankan migrasi 012 setelah skema dan seed diimpor.
3. Klik dua kali **`jalankan.bat`**. Skrip memvalidasi konfigurasi lalu menjalankan Apache 2.2 pada `127.0.0.1:8000`, terpisah dari Apache MoWeS, dan meneruskan skrip PHP ke PHP 8.2 CGI. Konfigurasi PHP lokal untuk Apache berada di `tools/apache/php-conf`; sesi PHP disimpan di `storage/sessions` dan memerlukan hak tulis.
4. Browser akan membuka alamat:
   - Portal Utama: **`http://127.0.0.1:8000`**
   - Halaman Masuk: **`http://127.0.0.1:8000/login`**
5. Apache memakai MPM Windows dengan 32 thread untuk melayani request secara bersamaan. Ubah `ThreadsPerChild` di `tools/apache/httpd-gmki.conf` jika kapasitas perlu disesuaikan; setiap request PHP dijalankan oleh PHP-CGI.

---

## 🚀 Panduan Peluncuran Masif ke Server Produksi (Production Deployment)

### 1. Kebutuhan Server
- **Sistem Operasi:** Linux (Ubuntu 22.04 LTS / Debian 12 / Rocky Linux) atau Windows Server.
- **PHP:** Versi 8.1 atau 8.2+ dengan ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl`, `curl`, `zlib`.
- **Web Server:** Nginx atau Apache 2.4+ (dengan modul `mod_rewrite` aktif).
- **Basis Data:** MariaDB 10.5+ atau MySQL 8.0+.

### 2. Konfigurasi DocumentRoot Apache
Pastikan konfigurasi virtual host mengarahkan DocumentRoot langsung ke folder `frontend/public`:
```apache
<VirtualHost *:80>
    ServerName gmkicabangpadang.or.id
    DocumentRoot /var/www/gmkicabangpadang/frontend/public

    <Directory /var/www/gmkicabangpadang/frontend/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/gmki_error.log
    CustomLog ${APACHE_LOG_DIR}/gmki_access.log combined
</VirtualHost>
```

### 3. Konfigurasi Nginx
```nginx
server {
    listen 80;
    server_name gmkicabangpadang.or.id;
    root /var/www/gmkicabangpadang/frontend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\. {
        deny all;
    }
}
```

### 4. Pengaturan Hak Akses Direktori Penyimpanan (Storage Permissions)
Jalankan perintah berikut agar server dapat menulis berkas unggahan, sesi, dan log cadangan:
```bash
chmod -R 775 storage/
chmod -R 775 frontend/public/uploads/
chown -R www-data:www-data storage/ frontend/public/uploads/
```

### 5. Konfigurasi Lingkungan (`.env`)
Salin berkas `.env.example` menjadi `.env` dan sesuaikan nilainya:
```ini
APP_NAME="GMKI Cabang Padang"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gmkicabangpadang.or.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gmki_padang
DB_USERNAME=user_produksi
DB_PASSWORD=kata_sandi_sangat_aman
```

---

## 🛡️ Jaminan Integritas & Fitur Keamanan Sistem

1. **Prepared Statements Penuh (Anti-SQLi):** Tidak ada query SQL langsung dengan penggabungan string (`concatenation`). Seluruh query di lapisan repository menggunakan parameter binding PDO.
2. **Perlindungan Cross-Site Request Forgery (CSRF):** Seluruh mutasi HTTP (POST/PUT/DELETE) diautentikasi dengan token kriptografis 64-karakter unik per sesi.
3. **Validasi Berkas Server-Side Ketat:** Pengunggahan gambar sampul, foto profil, dan dokumen menggunakan pemeriksaan MIME tipe asli melalui `finfo_file` untuk mencegah penyamaran skrip executable (`.php`, `.exe`).
4. **Penyimpanan Privat Tertutup:** Berkas sensitif (foto identitas civitas dan dokumen KTA) disimpan di luar DocumentRoot (`storage/private/`) dan hanya dapat diakses melalui controller otentikasi.
5. **Jejak Audit Kronologis (Audit Trail):** Setiap tindakan penambahan, pengubahan, penghapusan, impor, dan pencadangan dicatat lengkap beserta timestamp, username, role, dan alamat IP pelaku.
6. **Cadangan Data & SHA-256 Manifest:** Mendukung dump terkompresi otomatis dengan pembatasan 3 berkas terbaru serta verifikasi keaslian berkas unggahan dengan hash SHA-256.

---

## 🌐 Akses Publik & Demonstrasi Online via Ngrok

Aplikasi ini telah dilengkapi dengan binary **Ngrok portable** (`ngrok.exe`) dan skrip peluncur instan agar website dapat diakses langsung melalui internet (misal: di smartphone, tablet, atau demonstrasi pengurus tanpa perlu hosting):

### Antrean pekerjaan berat dan akses multi-pengguna
- Ekspor rekap laporan keuangan diproses oleh worker antrean, bukan saat halaman dibuka. Setelah migrasi 012, terapkan satu kali `backend/database/migrations/013_create_background_jobs.sql`, lalu jalankan `jalankan-queue.bat` di jendela terminal terpisah selama aplikasi digunakan. Status dan hasil ekspor tersedia pada menu **Antrean Ekspor**.
- Chat koordinasi menggunakan satu ruang grup yang sama untuk Ketcab, Sekcab, Bencab, dan Sekfung Medko. Riwayat chat antar-role yang sudah ada ikut tampil di ruang bersama. Akun Medko yang memakai dashboard Admin membuka ruang itu lewat `/admin/koordinasi`.
- Daftar inventaris, arsip, laporan, dan strategi dibatasi 25 data per halaman; indeks database mendukung penyortiran, antrean, dan pencarian percakapan. Aset statis di-cache browser selama satu jam dan skrip gaya utama tidak lagi menghalangi parsing halaman.
- Server lokal `jalankan.bat` memakai Apache MPM Windows dan PHP 8 CGI agar request HTTP dapat dilayani oleh beberapa thread. Ini membantu penggunaan lokal/demonstrasi; untuk trafik produksi, gunakan Apache/Nginx dengan PHP-FPM atau konfigurasi multi-worker yang sesuai hosting. Jangan menaruh request halaman ke tabel antrean aplikasi karena semua pengguna akan menunggu.

### Cara Menjalankan dengan Satu Klik:
1. Pastikan **MoWeS** (`mowes.exe`) sudah aktif (lampu Apache & MySQL hijau).
2. Klik ganda berkas **`jalankan-semua.bat`** di folder proyek.
   - Script ini otomatis membuka Apache lokal (Port 8000).
   - Script ini otomatis menyambungkan tunnel Ngrok online (HTTPS Publik).
3. Anda akan melihat URL publik aktif di jendela terminal Ngrok (contoh: `https://xxxx-xxxx.ngrok-free.dev`).
4. Untuk tautan absolut yang dibuat aplikasi, sesuaikan `APP_URL` di `.env` dengan URL publik tersebut, lalu buka URL Ngrok dari browser perangkat lain.

### Skrip Mandiri yang Tersedia:
- **`jalankan.bat`**: Menjalankan Apache lokal dengan PHP 8 CGI di `http://127.0.0.1:8000`.
- **`jalankan-ngrok.bat`**: Menjalankan tunnel online Ngrok meneruskan port 8000.
- **`jalankan-semua.bat`**: Menjalankan server lokal sekaligus tunnel Ngrok secara bersamaan.
- **Panel Web Inspeksi Ngrok**: Buka `http://127.0.0.1:4040` untuk melihat lalu lintas request real-time.

---

**Ut Omnes Unum Sint!**  
*Tinggi Iman, Tinggi Ilmu, Tinggi Pengabdian.*  
Badan Pengurus Cabang GMKI Cabang Padang.
