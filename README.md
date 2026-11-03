## Fitur
-**Dibuat Dengan PHP Native**
- **Autentikasi** – Sistem login menggunakan session untuk membatasi akses pengguna.
- **Manajemen Data Mahasiswa (CRUD)** –
- **Validasi Form** – Validasi input di sisi server untuk mencegah data yang tidak valid.
- **Pencegahan SQL Injection** – Menggunakan MySQLi prepared statements untuk menangani query database dengan lebih aman.
- **Pencegahan Duplikat Data** – Mencegah penggunaan NPM dan email yang sudah terdaftar.
- **Pencarian Mahasiswa** – Mencari data mahasiswa berdasarkan nama atau NPM.
- **Ganti Password User** - Mengganti password user untuk keamanan.
- **Pesan Lebih Interaktif** - Membantu pengguna untuk lebih paham.
- **Filter Program Studi** – Menyaring data mahasiswa berdasarkan program studi.
- **Konfigurasi Dinamis** – Mengatur logo, favicon, dan judul header website melalui `config.php`.
- **Kustomisasi Tema** – Mengubah warna utama dan sekunder website melalui variabel CSS di `style.css`.

## Kustomisasi

Pengaturan identitas website dapat diubah melalui `config/config.php`, meliputi:

- Logo website
- Favicon
- Judul header

Warna tampilan website dapat disesuaikan melalui variabel CSS di `assets/css/style.css`:

- `--primary-color`
- `--secondary-color`

Dengan begitu, tampilan website dapat diubah tanpa perlu memodifikasi kode PHP.

## Instalasi dan Penggunaan

### 1. Clone Repository

```bash
git clone https://github.com/berlandprastyo/crud-mahasiswa.git
cd crud-mahasiswa
```

### 2. Konfigurasi Database
- Import file `database/db_mahasiswa.sql` melalui phpMyAdmin atau MySQL command line.

### 3. Konfigurasi Aplikasi

- Ubah nama file `config/config.php.example` menjadi `config/config.php`.
- Buka file `config/config.php` dan sesuaikan kredensial database:

```php
$dbhost = "";
$dbuser = "";
$dbpassword = "";
$dbname = "db_mahasiswa";
```

### 4. Menambahkan Logo Website

- Letakkan file logo di dalam folder `assets/images/`.
- Gunakan nama file `logo.png`.
- Pastikan path logo di `config.php` sesuai dengan lokasi file tersebut.

### 5. Login

Gunakan akun administrator bawaan berikut:

| Keterangan | Nilai |
|---|---|
| Username | `admin` |
| Password | `admin` |

**Catatan:** Ganti password administrator bawaan segera!.
