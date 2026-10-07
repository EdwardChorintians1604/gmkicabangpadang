# Direktori Utilitas & Perkakas (Tools) - GMKI Cabang Padang

Folder ini berisi skrip utilitas mandiri (*standalone CLI tools*) untuk mempermudah pemeliharaan sistem, pengecekan akun, dan manajemen password.

> [!NOTE]
> Semua skrip di folder ini berada di luar folder publik (`frontend/public/`), sehingga **aman dari akses peramban luar (web browser)** dan hanya bisa dijalankan lewat terminal/konsol.

---

## 1. Generator Hash Bcrypt (`generate_hash.php`)
Membuat kode hash acak Bcrypt 60 karakter yang valid untuk kata sandi apa pun.

**Cara Penggunaan:**
```bash
# Membuat hash untuk kata sandi khusus:
E:\WebProgBPNama\php8\php.exe tools/generate_hash.php KataSandiRahasia123
```
Jangan gunakan kata sandi aktual pada argumen perintah di lingkungan bersama; argumen dapat tersimpan pada riwayat terminal atau terlihat di daftar proses.

---

## 2. Inspeksi Pengguna Database (`check_users.php`)
Melihat daftar seluruh akun di tabel `users`, mengecek status aktif/nonaktif, hak akses (role), waktu login terakhir, serta mencocokkan password dengan daftar kunci yang diketahui.

**Cara Penggunaan:**
```bash
E:\WebProgBPNama\php8\php.exe tools/check_users.php
```

---

## 3. Perbarui Kata Sandi (`update_user_password.php`)
Utilitas CLI lama ini menerima kata sandi sebagai argumen, sehingga nilai tersebut dapat terlihat pada riwayat perintah atau daftar proses. Untuk akun aktif, utamakan fitur **Ubah Kata Sandi** saat login atau pengelolaan akun administrator, dan jangan menaruh kata sandi aktual dalam dokumentasi maupun skrip.

**Cara Penggunaan:**
```bash
E:\WebProgBPNama\php8\php.exe tools/update_user_password.php <username> <password_baru>
```
