# Direktori Utilitas & Perkakas (Tools) - GMKI Cabang Padang

Folder ini berisi skrip utilitas mandiri (*standalone CLI tools*) untuk mempermudah pemeliharaan sistem, pengecekan akun, dan manajemen password.

> [!NOTE]
> Semua skrip di folder ini berada di luar folder publik (`frontend/public/`), sehingga **aman dari akses peramban luar (web browser)** dan hanya bisa dijalankan lewat terminal/konsol.

---

## 1. Generator Hash Bcrypt (`generate_hash.php`)
Membuat kode hash acak Bcrypt 60 karakter yang valid untuk kata sandi apa pun.

**Cara Penggunaan:**
```bash
# Menampilkan hash untuk daftar password bawaan:
E:\WebProgBPNama\php8\php.exe tools/generate_hash.php

# Atau membuat hash untuk kata sandi khusus:
E:\WebProgBPNama\php8\php.exe tools/generate_hash.php KataSandiRahasia123
```

---

## 2. Inspeksi Pengguna Database (`check_users.php`)
Melihat daftar seluruh akun di tabel `users`, mengecek status aktif/nonaktif, hak akses (role), waktu login terakhir, serta mencocokkan password dengan daftar kunci yang diketahui.

**Cara Penggunaan:**
```bash
E:\WebProgBPNama\php8\php.exe tools/check_users.php
```

---

## 3. Perbarui Kata Sandi Langsung (`update_user_password.php`)
Mengganti kata sandi pengguna langsung dari terminal tanpa perlu membuka phpMyAdmin atau kalkulator hash manual. Kata sandi otomatis di-hash Bcrypt sebelum disimpan.

**Cara Penggunaan:**
```bash
E:\WebProgBPNama\php8\php.exe tools/update_user_password.php <username> <password_baru>
```
**Contoh:**
```bash
E:\WebProgBPNama\php8\php.exe tools/update_user_password.php MacTavish0987 mactavish00
```
