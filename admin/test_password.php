<?php
// Test password hash untuk admin123
$password = 'admin123';
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<h2>Test Password Hash</h2>";
echo "<p>Password: $password</p>";
echo "<p>Hash: $hash</p>";

$result = password_verify($password, $hash);
echo "<p>Password verify result: " . ($result ? 'TRUE' : 'FALSE') . "</p>";

if ($result) {
    echo "<p style='color: green;'>✓ Password hash valid!</p>";
} else {
    echo "<p style='color: red;'>✗ Password hash tidak valid!</p>";
    
    // Buat hash baru
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<p>Hash baru untuk '$password': $new_hash</p>";
    
    // Test hash baru
    $new_result = password_verify($password, $new_hash);
    echo "<p>Test hash baru: " . ($new_result ? 'TRUE' : 'FALSE') . "</p>";
}

echo "<hr>";
echo "<p><a href='fix_password.php'>Jalankan fix_password.php</a></p>";
echo "<p><a href='login.php'>Coba login</a></p>";
?> 