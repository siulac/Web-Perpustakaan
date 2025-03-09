# 📚 Aplikasi Manajemen Perpustakaan

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Aplikasi Manajemen Perpustakaan adalah sistem informasi berbasis web yang dirancang untuk memudahkan pengelolaan data buku di perpustakaan. Dengan antarmuka modern dan fitur lengkap, aplikasi ini memungkinkan admin untuk mengelola koleksi buku secara efisien.

## ✨ Demo

Aplikasi demo dapat diakses di: [aplikasi-perpustakaan-iz.wuaze.com](http://aplikasi-perpustakaan-iz.wuaze.com)

**Kredensial Login:**

-   Username: `admin`
-   Password: `admin123`

## 🚀 Fitur

-   🔐 Sistem autentikasi admin yang aman
-   📝 Manajemen data buku (tambah, lihat, edit, hapus)
-   🖼️ Upload dan tampilan gambar sampul buku
-   🔍 Pencarian buku berdasarkan judul dan pengarang
-   📊 Filter dan pengurutan buku berdasarkan genre, judul, dan tahun terbit
-   📋 Laporan koleksi buku dalam format PDF
-   🌙 Tampilan dark mode yang elegan
-   📱 Responsif untuk berbagai ukuran perangkat

## 🛠️ Teknologi yang Digunakan

-   **Front-end**: HTML5, CSS3, JavaScript
-   **Back-end**: PHP 7.4+
-   **Database**: MySQL/MariaDB
-   **Library**: DOMPDF untuk generasi laporan PDF
-   **Framework CSS**: Font Awesome untuk ikon
-   **Koneksi Database**: PDO untuk keamanan yang lebih baik

## 📋 Persyaratan Sistem

-   PHP 7.4 atau lebih tinggi
-   MySQL 5.7 atau lebih tinggi
-   Web server (Apache/Nginx)
-   Ekstensi PHP: PDO, GD

## 📥 Instalasi

1. Clone repositori ini:

    ```bash
    git clone https://github.com/faiz-hidayat/Web-Perpustakaan.git
    ```

2. Pindah ke direktori proyek:

    ```bash
    cd Web-Perpustakaan
    ```

3. Buat database baru:

    ```sql
    CREATE DATABASE perpustakaan;
    ```

4. Import struktur database:

    ```sql
    -- Tabel admin
    CREATE TABLE admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    );

    -- Tabel buku
    CREATE TABLE buku (
        id INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(100) NOT NULL,
        pengarang VARCHAR(100) NOT NULL,
        tahun_terbit YEAR,
        genre VARCHAR(50),
        cover VARCHAR(255)
    );

    -- Data admin default
    INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123'));
    ```

5. Konfigurasi koneksi database:

    - Sesuaikan kredensial database pada file `koneksi.php`

6. Buat folder `uploads` untuk menyimpan gambar sampul:

    ```bash
    mkdir uploads
    chmod 755 uploads
    ```

7. Akses aplikasi melalui browser:
    ```
    http://localhost/Web-Perpustakaan
    ```

## 💻 Cara Penggunaan

### Login Admin

-   Gunakan username `admin` dan password `admin123` untuk masuk

### Mengelola Buku

1. **Menambah Buku**:

    - Klik tombol "Add Book" di dashboard
    - Isi formulir dengan judul, pengarang, genre, tahun terbit
    - Upload gambar sampul (opsional)
    - Klik "Save Book" untuk menyimpan

2. **Mencari Buku**:

    - Gunakan kolom pencarian di bagian atas halaman
    - Masukkan judul atau nama pengarang

3. **Memfilter Buku**:

    - Pilih genre dari filter yang tersedia
    - Klik "Apply Filter" untuk menerapkan filter

4. **Mengurutkan Buku**:

    - Klik header kolom untuk mengurutkan berdasarkan kolom tersebut
    - Klik sekali lagi untuk mengubah urutan (ascending/descending)

5. **Mengedit Buku**:

    - Klik ikon edit pada baris buku yang ingin diubah
    - Update informasi yang diperlukan
    - Klik "Update Book" untuk menyimpan perubahan

6. **Menghapus Buku**:

    - Klik ikon hapus pada baris buku yang ingin dihapus
    - Konfirmasi penghapusan

7. **Mengunduh Laporan**:
    - Klik tombol "Generate Report" untuk mengunduh daftar buku dalam format PDF

## 📸 Screenshot

![Dashboard](/screenshots/dashboard.png)

## 👨‍💻 Pengembang

Dikembangkan oleh [Muhammad Faiz Hidayat](https://github.com/faiz-hidayat)

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
