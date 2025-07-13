<?php
include '../database/config.php';

// Hapus admin yang ada (jika ada)
$delete_query = "DELETE FROM tb_admin WHERE username = 'admin'";
mysqli_query($conn, $delete_query);

// Buat password hash yang benar
$password = 'admin123';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert admin baru
$insert_query = "INSERT INTO tb_admin (username, password, nama, email, level, status) VALUES 
                ('admin', '$password_hash', 'Administrator', 'admin@kostmanager.com', 'super_admin', 'aktif')";

if (mysqli_query($conn, $insert_query)) {
    echo "Admin berhasil dibuat!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "Password Hash: $password_hash<br>";
    echo "<a href='login.php'>Klik di sini untuk login</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?> 