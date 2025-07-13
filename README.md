# Sistem Manajemen Kost

Aplikasi web berbasis PHP native untuk mengelola data kost, penghuni, dan kamar.

## Fitur Utama

- **Dashboard**: Menampilkan statistik dan ringkasan data kost
- **Manajemen Penghuni**: CRUD data penghuni kost
- **Manajemen Kamar**: CRUD data kamar dan harga sewa
- **Laporan**: Laporan keuangan dan statistik kost
- **Interface Modern**: Desain responsif dengan Bootstrap 5

## Struktur Database

### Tabel tb_penghuni
- `id` (Primary Key, Auto Increment)
- `nama` (VARCHAR 100) - Nama lengkap penghuni
- `no_ktp` (VARCHAR 16) - Nomor KTP (Unique)
- `no_hp` (VARCHAR 15) - Nomor handphone
- `tgl_masuk` (DATE) - Tanggal masuk kost
- `tgl_keluar` (DATE, NULL) - Tanggal keluar kost
- `kamar_id` (INT, Foreign Key) - ID kamar yang ditempati
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

### Tabel tb_kamar
- `id` (Primary Key, Auto Increment)
- `nomor` (VARCHAR 10) - Nomor kamar (Unique)
- `harga` (DECIMAL 10,2) - Harga sewa kamar
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

## Instalasi

### Prasyarat
- XAMPP/WAMP/LAMP Server
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web browser modern

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone [repository-url]
   cd app_kost
   ```

2. **Setup Database**
   - Buka phpMyAdmin atau MySQL client
   - Import file `database/db_kost.sql`
   - Atau jalankan query SQL secara manual

3. **Konfigurasi Database**
   - Edit file `database/config.php`
   - Sesuaikan host, username, password, dan nama database
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $database = 'db_kost';
   ```

4. **Akses Aplikasi**
   - Buka web browser
   - Akses `http://localhost/app_kost/`

## Penggunaan

### Dashboard
- Melihat statistik total kamar, penghuni aktif, kamar tersedia
- Melihat penghuni terbaru dan status kamar

### Data Penghuni
- **Tambah Penghuni**: Isi form dengan data lengkap penghuni
- **Edit Penghuni**: Klik tombol edit untuk mengubah data
- **Hapus Penghuni**: Klik tombol hapus untuk menghapus data
- **Status Penghuni**: Aktif (tgl_keluar kosong) atau Keluar (tgl_keluar terisi)

### Data Kamar
- **Tambah Kamar**: Isi nomor kamar dan harga sewa
- **Edit Kamar**: Ubah data kamar yang sudah ada
- **Hapus Kamar**: Hapus data kamar (hati-hati jika ada penghuni)
- **Status Kamar**: Tersedia atau Terisi

### Laporan
- Laporan penghuni (aktif dan keluar)
- Laporan kamar dengan status
- Laporan keuangan (pendapatan aktual dan potensial)

## Fitur Keamanan

- Validasi input form
- Escape string untuk mencegah SQL injection
- Konfirmasi sebelum menghapus data
- Session management

## Struktur File

```
app_kost/
├── database/
│   ├── config.php          # Konfigurasi database
│   └── db_kost.sql         # File SQL database
├── index.php               # Dashboard utama
├── penghuni.php            # Manajemen data penghuni
├── kamar.php               # Manajemen data kamar
├── laporan.php             # Halaman laporan
└── README.md               # Dokumentasi
```

## Teknologi yang Digunakan

- **Backend**: PHP Native
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Server**: Apache (XAMPP)

## Kontribusi

Silakan berkontribusi dengan:
1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Project ini menggunakan lisensi MIT.