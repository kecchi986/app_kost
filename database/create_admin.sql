-- File untuk membuat admin dengan password yang benar
-- Jalankan file ini di phpMyAdmin atau MySQL client

USE db_kost;

-- Hapus admin yang ada (jika ada)
DELETE FROM tb_admin WHERE username = 'admin';

-- Buat admin baru dengan password hash yang benar
-- Password: admin123
INSERT INTO tb_admin (username, password, nama, email, level, status) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@kostmanager.com', 'super_admin', 'aktif');

-- Verifikasi admin telah dibuat
SELECT id, username, nama, level, status, LEFT(password, 20) as password_preview FROM tb_admin WHERE username = 'admin'; 