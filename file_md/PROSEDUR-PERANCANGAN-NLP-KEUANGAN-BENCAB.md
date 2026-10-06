# Prosedur Perancangan: Modul Analisis Keuangan Bencab (NLP Berbasis PHP)

> **Dokumen Arsitektur & Spesifikasi Desain Sistem**  
> **Sasaran Pengguna:** Bendahara Cabang (BENCAB) & Panel Pengawas GMKI Cabang Padang  
> **Platform Target:** PHP 8+ (Kompatibel dengan Hosting InfinityFree / cPanel)

---

## 1. Latar Belakang & Tujuan Sistem

Peran **Bendahara Cabang (BENCAB)** di organisasi sering terkendala oleh proses pencatatan akuntansi manual yang kaku. Banyak transaksi kas masuk dan keluar tercecer di catatan WhatsApp, memo ponsel, atau sobekan kuitansi.

**Tujuan Modul:**
1. Menyediakan antarmuka ramah pengguna di mana Bencab cukup mengirimkan catatan teks bebas, nota, atau file rekapitulasi.
2. Menggunakan **Natural Language Processing (NLP) / Heuristic Information Extraction** yang berjalan murni di PHP untuk mengekstrak nominal, arah arus kas (pemasukan/pengeluaran), deskripsi, dan kategori.
3. Menerapkan prinsip **Master Copy vs Working Copy** untuk menjaga keaslian arsip keuangan dari manipulasi.
4. Mengoptimalkan penyimpanan agar **tidak melebihi batas kuota 5 GB** di hosting gratis InfinityFree.

---

## 2. Prinsip Integritas Dokumen (Master vs Working Copy)

Untuk memastikan akuntabilitas saat pemeriksaan oleh MPPC (Majelis Pertimbangan Pengawas Cabang):

```text
[Bencab Unggah Berkas Laporan / Nota]
                 │
                 ▼
        [Sistem Menerima Berkas]
                 │
        ┌────────┴────────────────────────┐
        ▼                                 ▼
1. Master Copy (Arsip Asli)       2. Working Copy (Duplikat Kerja)
- Kunci Read-Only                 - Salinan untuk diproses/di-parse
- Hitung Hash SHA-256             - Ekstraksi teks oleh NLP
- Disimpan di /private/arsip/     - Menampilkan pratinjau data
        │                                 │
        ▼                                 ▼
[Segel Forensik Tidak Berubah]    [Konfirmasi Bencab -> Simpan ke Kas]
```

### Aturan Arsip:
* **Arsip Asli (Master):** Tidak boleh diedit atau dipotong. Nilai *hash* SHA-256 dicatat di database pada detik berkas diterima sebagai bukti forensik digital bahwa dokumen asli tidak dimanipulasi.
* **Duplikat Kerja (Working Copy):** Berkas kerja yang dibuka, diekstrak isinya oleh parser, dan jika berupa gambar dikompresi untuk kebutuhan display pratinjau.

---

## 3. Arsitektur Pemrosesan NLP di PHP (Ramah InfinityFree)

InfinityFree tidak mengizinkan *daemon* Python atau perintah terminal `shell_exec`. Oleh karena itu, arsitektur dirancang menggunakan **Dua Lapisan Pengolahan di PHP**:

### Lapisan 1: Native PHP Rule-Based & Regex NLP (Default & Instan)
Berjalan 100% di server lokal dalam waktu < 0,01 detik tanpa koneksi internet luar:
1. **Ekstraksi Nominal & Satuan:**
   * Pola regex menangkap variasi angka: `50rb`, `50.000`, `1,5 jt`, `1500k`, `Rp 25.000`.
   * Normalisasi otomatis menjadi integer murni: `50000`, `1500000`, `25000`.
2. **Ekstraksi Arah Kas (Debit vs Kredit):**
   * *Kredit (Masuk):* Deteksi kata kunci `[terima, masuk, transfer dari, iuran, sumbangan, donasi, sisa saldo, subsidi]`.
   * *Debit (Keluar):* Deteksi kata kunci `[beli, bayar, fotokopi, konsumsi, sewa, transport, keluar, biaya, belanja]`.
3. **Ekstraksi Kategori:**
   * Pencocokan kamus entitas:
     * `Konsumsi` $\leftarrow$ `[makan, minum, nasi, snack, aqua, konsumsi, roti]`
     * `Kesekretariatan` $\leftarrow$ `[spidol, kertas, jilid, amplop, cetak, stempel, materai]`
     * `Kaderisasi` $\leftarrow$ `[maperca, pemateri, sertifikat, modul, penginapan]`
     * `Aksi Pelayanan` $\leftarrow$ `[baksos, advokasi, bantuan, gereja, donasi ke]`

### Lapisan 2: Cloud AI API Fallback (Opsional untuk Paragraf Cerita Kompleks)
Jika teks catatan Bencab sangat panjang dan tidak berpola:
* PHP mengirim teks via `curl` standar ke Google Gemini Flash API (tersedia kuota gratis).
* Menerima balasan format JSON murni.

---

## 4. Alur Human-in-the-Loop (Validasi 1-Klik)

AI/NLP tidak boleh langsung menulis ke saldo utama tanpa pengawasan manusia:

1. **Input:** Bencab mengetik catatan di antarmuka input cepat atau unggah foto nota.
2. **Ekstraksi:** Sistem menampilkan kartu pratinjau dalam 1 detik:
   * *Jenis:* Pengeluaran
   * *Nominal:* Rp 85.000
   * *Kategori:* Konsumsi
   * *Keterangan:* Pembelian konsumsi rapat BPC
3. **Aksi Bencab:**
   * Jika benar: Klik tombol hijau **"Konfirmasi & Simpan ke Kas"**.
   * Jika ada koreksi angka: Bisa diedit langsung di form inline sebelum disimpan.

---

## 5. Strategi Penghematan Ruang (Mitigasi Kuota 5 GB InfinityFree)

Kapasitas 5 GB di InfinityFree sangat mencukupi apabila menerapkan kebijakan penyimpanan berikut:

| Jenis Data | Strategi Penyimpanan | Estimasi Ukuran |
| :--- | :--- | :--- |
| **Data Angka & Teks Transaksi** | Disimpan di tabel MySQL `kas_cabang` | ~1 KB per transaksi (10.000 transaksi = hanya ~10 MB) |
| **Bukti Foto Nota / Kuitansi** | Dikonversi otomatis ke format **WebP** (Lebar maks 1200px, kualitas 80%) | Turun dari 5 MB menjadi **~150–250 KB** per lembar |
| **Berkas Rekap Laporan Lama** | **Kebijakan Rotasi Arsip (FIFO):** Data aktif di server adalah 1 periode berjalan (2 tahun). Periode lama diekspor menjadi 1 arsip `.zip` untuk diunduh Bencab, kemudian file fisik lama dapat dibersihkan (*pruned*). | Menghemat hingga 80% ruang penyimpanan |

---

## 6. Rencana Struktur Tabel Database

```sql
-- 1. Tabel Buku Kas Cabang Terintegrasi
CREATE TABLE `keuangan_kas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode_transaksi` VARCHAR(30) NOT NULL UNIQUE,
    `tanggal_transaksi` DATE NOT NULL,
    `tipe` ENUM('masuk', 'keluar') NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `nominal` DECIMAL(15,2) NOT NULL,
    `keterangan` TEXT NOT NULL,
    `arsip_id` INT UNSIGNED NULL,
    `created_by` INT UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL
);

-- 2. Tabel Bukti Dokumen Asli vs Duplikat
CREATE TABLE `keuangan_arsip` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_berkas_asli` VARCHAR(255) NOT NULL,
    `path_arsip_master` VARCHAR(255) NOT NULL,
    `hash_sha256_master` VARCHAR(64) NOT NULL,
    `path_working_copy` VARCHAR(255) NULL,
    `ukuran_byte` INT UNSIGNED NOT NULL,
    `status_verifikasi` ENUM('asli_tervalidasi', 'duplikat_kerja') DEFAULT 'asli_tervalidasi',
    `created_at` DATETIME NOT NULL
);
```

---

## 7. Roadmap Implementasi Bertahap

* **Fase 1:** Pembuatan tabel database `keuangan_kas` dan `keuangan_arsip`.
* **Fase 2:** Pembuatan modul *Engine Parser* berbasis PHP Regex & Heuristic (`App\Services\FinanceNlpService`).
* **Fase 3:** Antarmuka Bencab di Panel Pengawas (Form input cepat + upload nota WebP + pratinjau konfirmasi).
* **Fase 4:** Dashboard Rekapitulasi Kas (Grafik arus kas bulanan, saldo kas terkini, dan log integritas SHA-256).
