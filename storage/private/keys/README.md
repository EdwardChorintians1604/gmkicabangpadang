# Panduan Kunci Kredensial Google Service Account

Folder ini digunakan untuk menyimpan file kunci kredensial JSON dari Google Cloud Console agar sistem dapat terhubung ke Google Drive API secara otomatis tanpa interaksi browser manual.

### Cara Mendapatkan File JSON:
1. Buka [Google Cloud Console](https://console.cloud.google.com/) menggunakan akun Google (Gmail) yang Anda persiapkan.
2. Buat Project baru (misal: `GMKI Cabang Padang`).
3. Masuk ke menu **APIs & Services > Library**, cari **Google Drive API**, lalu klik **Enable (Aktifkan)**.
4. Masuk ke menu **APIs & Services > Credentials**, klik **Create Credentials > Service Account**.
5. Beri nama akun (misal: `gmki-drive-storage`), lalu klik **Create and Continue**, dan klik **Done**.
6. Klik akun Service Account yang baru dibuat tersebut, lalu buka tab **Keys**.
7. Klik **Add Key > Create new key**, pilih format **JSON**, lalu klik **Create**. File `.json` akan terunduh ke komputer Anda.
8. Ganti nama file tersebut menjadi `google-service-account.json` dan letakkan di dalam folder ini (`storage/private/keys/google-service-account.json`).

### Menghubungkan Folder Google Drive:
1. Buka [Google Drive](https://drive.google.com/) menggunakan email Anda.
2. Buat folder baru (misal: `GMKI_Padang_Storage`).
3. Klik kanan folder tersebut > **Bagikan (Share)**.
4. Salin email Service Account (yang berakhiran `@...gserviceaccount.com`), tempelkan ke kolom bagikan, berikan akses sebagai **Editor**, lalu simpan.
5. Salin ID Folder dari bilah alamat browser Anda (rangkaian huruf & angka acak di akhir URL `drive.google.com/drive/folders/{FOLDER_ID}`).
6. Tempelkan ID tersebut ke file `.env`:
   ```env
   GDRIVE_ENABLED=true
   GDRIVE_FOLDER_ID="ID_FOLDER_ANDA"
   GDRIVE_OFFLOAD_LOCAL=true
   ```
