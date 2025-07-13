<?php
// Konfigurasi Database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_kost';

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset ke utf8
mysqli_set_charset($conn, "utf8");
?> 