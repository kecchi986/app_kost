# Sistem Manajemen Kost

Aplikasi web berbasis PHP native untuk mengelola data kost, penghuni, dan kamar.

## Fitur Utama

- **Dashboard**: Menampilkan statistik dan ringkasan data kost
- **Manajemen Penghuni**: CRUD data penghuni kost
- **Manajemen Kamar**: CRUD data kamar dan harga sewa
- **Manajemen Barang**: CRUD data barang biaya tambahan
- **Relasi Kamar-Penghuni**: Kelola data penghuni yang menempati kamar
- **Barang Bawaan**: Kelola barang yang dibawa/digunakan penghuni
- **Sistem Tagihan**: Generate dan kelola tagihan bulanan otomatis
- **Sistem Pembayaran**: Kelola pembayaran cicilan dan lunas
- **Pindah Kamar**: Kelola perpindahan kamar dan keluar kost
- **Laporan**: Laporan keuangan dan statistik kost
- **Interface Modern**: Desain responsif dengan Bootstrap 5

## Struktur Database

### Tabel tb_kamar
- `id` (Primary Key, Auto Increment)
- `nomor` (VARCHAR 10) - Nomor kamar (Unique)
- `harga` (DECIMAL 10,2) - Harga sewa kamar
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

### Tabel tb_barang
- `id` (Primary Key, Auto Increment)
- `nama` (VARCHAR 100) - Nama barang biaya tambahan
- `harga` (DECIMAL 10,2) - Harga barang
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

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

### Tabel tb_kmr_penghuni
- `id` (Primary Key, Auto Increment)
- `id_kamar` (INT, Foreign Key) - ID kamar
- `id_penghuni` (INT, Foreign Key) - ID penghuni
- `tgl_masuk` (DATE) - Tanggal masuk ke kamar
- `tgl_keluar` (DATE, NULL) - Tanggal keluar dari kamar
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

### Tabel tb_brng_bawaan
- `id` (Primary Key, Auto Increment)
- `id_penghuni` (INT, Foreign Key) - ID penghuni
- `id_barang` (INT, Foreign Key) - ID barang
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

### Tabel tb_tagihan
- `id` (Primary Key, Auto Increment)
- `bulan` (VARCHAR 7) - Format: YYYY-MM
- `id_kmr_penghuni` (INT, Foreign Key) - ID relasi kamar-penghuni
- `jml_tagihan` (DECIMAL 10,2) - Total tagihan (sewa + barang)
- `status_bayar` (ENUM) - Belum Bayar/Sudah Bayar
- `tgl_bayar` (DATE, NULL) - Tanggal pembayaran
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

### Tabel tb_bayar
- `id` (Primary Key, Auto Increment)
- `id_tagihan` (INT, Foreign Key) - ID tagihan
- `jml_bayar` (DECIMAL 10,2) - Jumlah pembayaran
- `status` (ENUM) - Cicil/Lunas
- `tgl_bayar` (DATE) - Tanggal pembayaran
- `keterangan` (TEXT, NULL) - Keterangan pembayaran
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

### Data Barang
- **Tambah Barang**: Isi nama barang dan harga biaya tambahan
- **Edit Barang**: Ubah data barang yang sudah ada
- **Hapus Barang**: Hapus data barang
- **Contoh Barang**: WiFi, Listrik, Air, Kebersihan, Parkir

### Relasi Kamar-Penghuni
- **Tambah Relasi**: Hubungkan penghuni dengan kamar tertentu
- **Edit Relasi**: Ubah data relasi kamar-penghuni
- **Hapus Relasi**: Hapus data relasi
- **Status Relasi**: Aktif (tgl_keluar kosong) atau Keluar (tgl_keluar terisi)

### Barang Bawaan
- **Tambah Barang Bawaan**: Pilih penghuni dan barang yang digunakan
- **Hapus Barang Bawaan**: Hapus barang yang tidak digunakan lagi
- **Validasi**: Mencegah duplikasi barang untuk penghuni yang sama

### Sistem Tagihan
- **Generate Otomatis**: Buat tagihan bulanan untuk semua penghuni aktif
- **Perhitungan Otomatis**: Tagihan = Harga Sewa + Total Barang Bawaan
- **Status Pembayaran**: Update status Belum Bayar/Sudah Bayar
- **Riwayat Tagihan**: Melihat semua tagihan per bulan

### Sistem Pembayaran
- **Tambah Pembayaran**: Catat pembayaran cicilan atau lunas
- **Edit Pembayaran**: Ubah data pembayaran yang sudah ada
- **Hapus Pembayaran**: Hapus data pembayaran
- **Status Pembayaran**: Cicil atau Lunas
- **Riwayat Pembayaran**: Melihat semua pembayaran per tagihan

### Pindah Kamar & Keluar Kost
- **Pindah Kamar**: Proses perpindahan penghuni ke kamar lain
- **Keluar Kost**: Proses penghuni keluar dari kost
- **Riwayat Pindah**: Melihat riwayat perpindahan kamar
- **Otomatis Update**: Update tgl_keluar di tb_kmr_penghuni dan tb_penghuni
- **Hapus Barang Bawaan**: Otomatis hapus barang bawaan saat keluar kost

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
├── barang.php              # Manajemen data barang
├── relasi_kamar.php        # Manajemen relasi kamar-penghuni
├── barang_bawaan.php       # Manajemen barang bawaan
├── tagihan.php             # Manajemen tagihan
├── pembayaran.php          # Manajemen pembayaran
├── pindah_kamar.php        # Pindah kamar & keluar kost
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