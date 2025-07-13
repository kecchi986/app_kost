<?php
include '../database/config.php';

echo "<h2>Perbaikan Password Admin</h2>";

// Cek apakah tabel admin sudah ada
$check_table = "SHOW TABLES LIKE 'tb_admin'";
$table_exists = mysqli_query($conn, $check_table);

if (mysqli_num_rows($table_exists) == 0) {
    echo "<p style='color: red;'>Tabel tb_admin belum ada! Silakan import database terlebih dahulu.</p>";
    exit();
}

// Cek admin yang ada
$check_admin = "SELECT * FROM tb_admin WHERE username = 'admin'";
$result = mysqli_query($conn, $check_admin);

if (mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    echo "<p>Admin ditemukan:</p>";
    echo "<ul>";
    echo "<li>Username: " . $admin['username'] . "</li>";
    echo "<li>Nama: " . $admin['nama'] . "</li>";
    echo "<li>Level: " . $admin['level'] . "</li>";
    echo "<li>Status: " . $admin['status'] . "</li>";
    echo "<li>Password Hash: " . $admin['password'] . "</li>";
    echo "</ul>";
    
    // Test password verification
    $test_password = 'admin123';
    $is_valid = password_verify($test_password, $admin['password']);
    
    echo "<p>Test password 'admin123': " . ($is_valid ? "<span style='color: green;'>VALID</span>" : "<span style='color: red;'>TIDAK VALID</span>") . "</p>";
    
    if (!$is_valid) {
        echo "<p style='color: orange;'>Password hash tidak valid. Memperbaiki...</p>";
        
        // Update password dengan hash yang benar
        $new_password = 'admin123';
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $update_query = "UPDATE tb_admin SET password = '$new_hash' WHERE username = 'admin'";
        if (mysqli_query($conn, $update_query)) {
            echo "<p style='color: green;'>Password berhasil diperbaiki!</p>";
            echo "<p>Password Hash Baru: $new_hash</p>";
            
            // Test lagi
            $test_again = password_verify($test_password, $new_hash);
            echo "<p>Test password setelah perbaikan: " . ($test_again ? "<span style='color: green;'>VALID</span>" : "<span style='color: red;'>TIDAK VALID</span>") . "</p>";
        } else {
            echo "<p style='color: red;'>Error memperbaiki password: " . mysqli_error($conn) . "</p>";
        }
    }
} else {
    echo "<p style='color: red;'>Admin dengan username 'admin' tidak ditemukan!</p>";
    
    // Buat admin baru
    echo "<p>Membuat admin baru...</p>";
    $password = 'admin123';
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $insert_query = "INSERT INTO tb_admin (username, password, nama, email, level, status) VALUES 
                    ('admin', '$password_hash', 'Administrator', 'admin@kostmanager.com', 'super_admin', 'aktif')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "<p style='color: green;'>Admin berhasil dibuat!</p>";
        echo "<p>Username: admin</p>";
        echo "<p>Password: admin123</p>";
        echo "<p>Password Hash: $password_hash</p>";
    } else {
        echo "<p style='color: red;'>Error membuat admin: " . mysqli_error($conn) . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Klik di sini untuk login</a></p>";
echo "<p><a href='../index.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Kembali ke Halaman Depan</a></p>";
?> 