-- Database: db_kost
CREATE DATABASE IF NOT EXISTS db_kost;
USE db_kost;

-- Tabel tb_kamar
CREATE TABLE IF NOT EXISTS tb_kamar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor VARCHAR(10) NOT NULL UNIQUE,
    harga DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel tb_penghuni
CREATE TABLE IF NOT EXISTS tb_penghuni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_ktp VARCHAR(16) NOT NULL UNIQUE,
    no_hp VARCHAR(15) NOT NULL,
    tgl_masuk DATE NOT NULL,
    tgl_keluar DATE NULL,
    kamar_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kamar_id) REFERENCES tb_kamar(id) ON DELETE SET NULL
);

-- Insert data contoh untuk kamar
INSERT INTO tb_kamar (nomor, harga) VALUES 
('A1', 800000),
('A2', 800000),
('A3', 850000),
('B1', 900000),
('B2', 900000),
('B3', 950000);

-- Insert data contoh untuk penghuni
INSERT INTO tb_penghuni (nama, no_ktp, no_hp, tgl_masuk, kamar_id) VALUES 
('Ahmad Rizki', '1234567890123456', '081234567890', '2024-01-15', 1),
('Siti Nurhaliza', '2345678901234567', '081234567891', '2024-01-20', 2),
('Budi Santoso', '3456789012345678', '081234567892', '2024-02-01', 3); 