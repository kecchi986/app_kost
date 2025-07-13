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

-- Tabel tb_barang
CREATE TABLE IF NOT EXISTS tb_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
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

-- Tabel tb_kmr_penghuni (relasi penghuni-kamar)
CREATE TABLE IF NOT EXISTS tb_kmr_penghuni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kamar INT NOT NULL,
    id_penghuni INT NOT NULL,
    tgl_masuk DATE NOT NULL,
    tgl_keluar DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kamar) REFERENCES tb_kamar(id) ON DELETE CASCADE,
    FOREIGN KEY (id_penghuni) REFERENCES tb_penghuni(id) ON DELETE CASCADE
);

-- Tabel tb_brng_bawaan (barang bawaan penghuni)
CREATE TABLE IF NOT EXISTS tb_brng_bawaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_penghuni INT NOT NULL,
    id_barang INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penghuni) REFERENCES tb_penghuni(id) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES tb_barang(id) ON DELETE CASCADE
);

-- Tabel tb_tagihan
CREATE TABLE IF NOT EXISTS tb_tagihan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bulan VARCHAR(7) NOT NULL, -- Format: YYYY-MM
    id_kmr_penghuni INT NOT NULL,
    jml_tagihan DECIMAL(10,2) NOT NULL,
    status_bayar ENUM('Belum Bayar', 'Sudah Bayar') DEFAULT 'Belum Bayar',
    tgl_bayar DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kmr_penghuni) REFERENCES tb_kmr_penghuni(id) ON DELETE CASCADE
);

-- Tabel tb_bayar (pembayaran cicilan)
CREATE TABLE IF NOT EXISTS tb_bayar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_tagihan INT NOT NULL,
    jml_bayar DECIMAL(10,2) NOT NULL,
    status ENUM('Cicil', 'Lunas') DEFAULT 'Cicil',
    tgl_bayar DATE NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tagihan) REFERENCES tb_tagihan(id) ON DELETE CASCADE
);

-- Insert data contoh untuk kamar
INSERT INTO tb_kamar (nomor, harga) VALUES 
('A1', 800000),
('A2', 800000),
('A3', 850000),
('B1', 900000),
('B2', 900000),
('B3', 950000);

-- Insert data contoh untuk barang
INSERT INTO tb_barang (nama, harga) VALUES 
('WiFi', 50000),
('Listrik', 100000),
('Air', 30000),
('Kebersihan', 25000),
('Parkir Motor', 20000),
('Parkir Mobil', 50000);

-- Insert data contoh untuk penghuni
INSERT INTO tb_penghuni (nama, no_ktp, no_hp, tgl_masuk, kamar_id) VALUES 
('Ahmad Rizki', '1234567890123456', '081234567890', '2024-01-15', 1),
('Siti Nurhaliza', '2345678901234567', '081234567891', '2024-01-20', 2),
('Budi Santoso', '3456789012345678', '081234567892', '2024-02-01', 3);

-- Insert data contoh untuk relasi kamar-penghuni
INSERT INTO tb_kmr_penghuni (id_kamar, id_penghuni, tgl_masuk) VALUES 
(1, 1, '2024-01-15'),
(2, 2, '2024-01-20'),
(3, 3, '2024-02-01');

-- Insert data contoh untuk barang bawaan
INSERT INTO tb_brng_bawaan (id_penghuni, id_barang) VALUES 
(1, 1), -- Ahmad Rizki menggunakan WiFi
(1, 2), -- Ahmad Rizki menggunakan Listrik
(2, 1), -- Siti Nurhaliza menggunakan WiFi
(2, 3), -- Siti Nurhaliza menggunakan Air
(3, 1), -- Budi Santoso menggunakan WiFi
(3, 2), -- Budi Santoso menggunakan Listrik
(3, 4); -- Budi Santoso menggunakan Kebersihan

-- Insert data contoh untuk tagihan
INSERT INTO tb_tagihan (bulan, id_kmr_penghuni, jml_tagihan) VALUES 
('2024-01', 1, 950000), -- Ahmad Rizki: 800000 (kamar) + 150000 (WiFi+Listrik)
('2024-01', 2, 880000), -- Siti Nurhaliza: 800000 (kamar) + 80000 (WiFi+Air)
('2024-02', 1, 950000), -- Ahmad Rizki Februari
('2024-02', 2, 880000), -- Siti Nurhaliza Februari
('2024-02', 3, 1025000); -- Budi Santoso: 900000 (kamar) + 125000 (WiFi+Listrik+Kebersihan)

-- Insert data contoh untuk pembayaran
INSERT INTO tb_bayar (id_tagihan, jml_bayar, status, tgl_bayar, keterangan) VALUES 
(1, 500000, 'Cicil', '2024-01-15', 'Cicilan pertama'),
(1, 450000, 'Lunas', '2024-01-20', 'Pelunasan'),
(2, 880000, 'Lunas', '2024-01-20', 'Bayar lunas'),
(3, 300000, 'Cicil', '2024-02-01', 'Cicilan pertama Februari'); 