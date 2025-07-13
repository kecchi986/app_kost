<?php
include '../database/config.php';

echo "<h2>Pengecekan Database Admin</h2>";

// Cek koneksi database
if (!$conn) {
    echo "<p style='color: red;'>Error: Tidak dapat terhubung ke database!</p>";
    exit();
}

echo "<p style='color: green;'>✓ Koneksi database berhasil</p>";

// Cek apakah tabel admin ada
$check_table = "SHOW TABLES LIKE 'tb_admin'";
$table_result = mysqli_query($conn, $check_table);

if (mysqli_num_rows($table_result) == 0) {
    echo "<p style='color: red;'>✗ Tabel tb_admin tidak ditemukan!</p>";
    echo "<p>Silakan import file database/db_kost.sql terlebih dahulu.</p>";
} else {
    echo "<p style='color: green;'>✓ Tabel tb_admin ditemukan</p>";
    
    // Cek struktur tabel
    $describe_table = "DESCRIBE tb_admin";
    $desc_result = mysqli_query($conn, $describe_table);
    
    echo "<h3>Struktur Tabel tb_admin:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($desc_result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Cek data admin
    $check_admin = "SELECT * FROM tb_admin";
    $admin_result = mysqli_query($conn, $check_admin);
    
    echo "<h3>Data Admin:</h3>";
    if (mysqli_num_rows($admin_result) == 0) {
        echo "<p style='color: orange;'>Tidak ada data admin</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Nama</th><th>Email</th><th>Level</th><th>Status</th><th>Password Hash</th></tr>";
        while ($admin = mysqli_fetch_assoc($admin_result)) {
            echo "<tr>";
            echo "<td>" . $admin['id'] . "</td>";
            echo "<td>" . $admin['username'] . "</td>";
            echo "<td>" . $admin['nama'] . "</td>";
            echo "<td>" . $admin['email'] . "</td>";
            echo "<td>" . $admin['level'] . "</td>";
            echo "<td>" . $admin['status'] . "</td>";
            echo "<td style='font-size: 10px;'>" . substr($admin['password'], 0, 50) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

echo "<hr>";
echo "<h3>Langkah Perbaikan:</h3>";
echo "<ol>";
echo "<li><a href='fix_password.php' target='_blank'>Jalankan fix_password.php</a> untuk memperbaiki password</li>";
echo "<li><a href='login.php?debug=1' target='_blank'>Test login dengan debug</a></li>";
echo "<li><a href='login.php'>Login normal</a></li>";
echo "</ol>";

echo "<p><a href='../index.php'>Kembali ke Halaman Depan</a></p>";
?> 