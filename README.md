# SIM-KAD — Sistem Informasi & Manajemen Keanggotaan Terintegrasi

**GMKI Cabang Padang (Gerakan Mahasiswa Kristen Indonesia)**

SIM-KAD adalah aplikasi web untuk mendukung administrasi GMKI Cabang Padang: pendataan civitas, kaderisasi, publikasi warta cabang, dan pemantauan oleh pimpinan cabang.

---

## Fitur

- **Portal publik:** profil organisasi, struktur BPC, berita/warta, dan kontak.
- **Manajemen keanggotaan:** pendataan civitas dan KTA digital.
- **Publikasi warta:** penulisan dan penerbitan berita cabang.
- **Pemantauan pimpinan:** dashboard baca-saja untuk peninjauan eksekutif.
- **Statistik:** visualisasi data demografi anggota.

## Peran Pengguna

| Peran | Cakupan |
|---|---|
| Administrator | Pengelolaan penuh sistem |
| Operator | Pengelolaan data harian |
| Pengawas | Peninjauan baca-saja oleh pimpinan cabang |

## Teknologi

PHP (MVC native), MySQL/MariaDB, HTML/CSS/JavaScript, Chart.js, Composer.

---

## Pengembangan Lokal

```bash
git clone https://github.com/<username>/<nama-repo>.git
cd <nama-repo>
composer install
cp .env.example .env
```

Lengkapi `.env` dengan nilai milik Anda sendiri, siapkan basis data kosong, lalu ikuti panduan pengembangan internal untuk langkah selanjutnya.

> Gunakan **data dummy** untuk pengembangan. Jangan pernah memakai data anggota yang asli.

## Deployment & Operasional

Panduan deployment, konfigurasi server, dan operasional **tidak dipublikasikan di repositori ini**. Hubungi pengurus yang berwenang untuk mendapatkannya.

---

## Keamanan & Privasi

- Repositori ini hanya berisi kode sumber. Kredensial, berkas konfigurasi lingkungan, data anggota, dan cadangan tidak disimpan di sini.
- Menemukan celah keamanan? **Jangan** membuka *issue* publik. Gunakan **Security → Report a vulnerability** di halaman repositori ini.

---

**Ut Omnes Unum Sint!**
*Tinggi Iman, Tinggi Ilmu, Tinggi Pengabdian.*
Badan Pengurus Cabang GMKI Cabang Padang