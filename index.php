<?php
session_start();
include 'database/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost Manager - Halaman Depan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-tersedia {
            background-color: #28a745;
            color: white;
        }
        .status-terisi {
            background-color: #dc3545;
            color: white;
        }
        .status-segera-bayar {
            background-color: #ffc107;
            color: black;
        }
        .status-terlambat {
            background-color: #dc3545;
            color: white;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home"></i> Kost Manager
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/">Admin Panel</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold">Selamat Datang di Kost Manager</h1>
                    <p class="lead">Temukan kamar kost yang nyaman dengan harga terjangkau</p>
                    <a href="#kamar-tersedia" class="btn btn-light btn-lg">
                        <i class="fas fa-bed"></i> Lihat Kamar Tersedia
                    </a>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-home fa-6x opacity-75"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Kamar Tersedia -->
        <section id="kamar-tersedia" class="mb-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4"><i class="fas fa-bed"></i> Kamar Tersedia</h2>
                </div>
            </div>
            <div class="row">
                <?php
                $query_kamar = "SELECT k.*, 
                               CASE WHEN kp.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                               FROM tb_kamar k 
                               LEFT JOIN tb_kmr_penghuni kp ON k.id = kp.id_kamar AND kp.tgl_keluar IS NULL
                               ORDER BY k.nomor";
                $result_kamar = mysqli_query($conn, $query_kamar);
                while ($kamar = mysqli_fetch_assoc($result_kamar)) {
                    $status_class = $kamar['status'] == 'Tersedia' ? 'status-tersedia' : 'status-terisi';
                    $card_class = $kamar['status'] == 'Tersedia' ? 'border-success' : 'border-danger';
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 <?php echo $card_class; ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-door-open"></i> Kamar <?php echo htmlspecialchars($kamar['nomor']); ?>
                                </h5>
                                <h3 class="text-primary mb-3">Rp <?php echo number_format($kamar['harga'], 0, ',', '.'); ?></h3>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $kamar['status']; ?>
                                </span>
                                <?php if ($kamar['status'] == 'Tersedia'): ?>
                                    <div class="mt-3">
                                        <a href="https://wa.me/6281234567890?text=Saya tertarik dengan kamar <?php echo $kamar['nomor']; ?> seharga Rp <?php echo number_format($kamar['harga'], 0, ',', '.'); ?>" 
                                           class="btn btn-success btn-sm" target="_blank">
                                            <i class="fab fa-whatsapp"></i> Hubungi Kami
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>

        <!-- Kamar Sebentar Lagi Bayar -->
        <section class="mb-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4"><i class="fas fa-clock"></i> Kamar Sebentar Lagi Bayar</h2>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Penghuni yang akan jatuh tempo dalam 7 hari ke depan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Penghuni</th>
                                    <th>Nomor Kamar</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_segera_bayar = "SELECT p.nama, k.nomor, kp.tgl_masuk, 
                                                      DATE_ADD(kp.tgl_masuk, INTERVAL 1 MONTH) as jatuh_tempo,
                                                      COALESCE(t.jml_tagihan, k.harga) as total_tagihan
                                                      FROM tb_penghuni p 
                                                      INNER JOIN tb_kmr_penghuni kp ON p.id = kp.id_penghuni AND kp.tgl_keluar IS NULL
                                                      INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                                      LEFT JOIN tb_tagihan t ON kp.id = t.id_kmr_penghuni 
                                                      AND t.bulan = DATE_FORMAT(DATE_ADD(kp.tgl_masuk, INTERVAL 1 MONTH), '%Y-%m')
                                                      WHERE p.tgl_keluar IS NULL 
                                                      AND DATE_ADD(kp.tgl_masuk, INTERVAL 1 MONTH) BETWEEN CURDATE() 
                                                      AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                                                      ORDER BY jatuh_tempo";
                                $result_segera_bayar = mysqli_query($conn, $query_segera_bayar);
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result_segera_bayar)) {
                                    $days_left = ceil((strtotime($row['jatuh_tempo']) - time()) / (60 * 60 * 24));
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td><strong>" . htmlspecialchars($row['nama']) . "</strong></td>";
                                    echo "<td>" . htmlspecialchars($row['nomor']) . "</td>";
                                    echo "<td>" . date('d/m/Y', strtotime($row['tgl_masuk'])) . "</td>";
                                    echo "<td><span class='status-badge status-segera-bayar'>" . date('d/m/Y', strtotime($row['jatuh_tempo'])) . " ($days_left hari lagi)</span></td>";
                                    echo "<td><strong>Rp " . number_format($row['total_tagihan'], 0, ',', '.') . "</strong></td>";
                                    echo "</tr>";
                                }
                                if (mysqli_num_rows($result_segera_bayar) == 0) {
                                    echo "<tr><td colspan='6' class='text-center text-muted'>Tidak ada penghuni yang sebentar lagi jatuh tempo</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kamar Terlambat Bayar -->
        <section class="mb-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4"><i class="fas fa-exclamation-triangle"></i> Kamar Terlambat Bayar</h2>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Penghuni yang terlambat membayar tagihan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Penghuni</th>
                                    <th>Nomor Kamar</th>
                                    <th>Bulan Tagihan</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Keterlambatan</th>
                                    <th>Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_terlambat = "SELECT p.nama, k.nomor, t.bulan, 
                                                   DATE_FORMAT(STR_TO_DATE(t.bulan, '%Y-%m'), '%Y-%m-10') as jatuh_tempo,
                                                   DATEDIFF(CURDATE(), DATE_FORMAT(STR_TO_DATE(t.bulan, '%Y-%m'), '%Y-%m-10')) as keterlambatan,
                                                   t.jml_tagihan
                                                   FROM tb_tagihan t 
                                                   INNER JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni = kp.id
                                                   INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                                   INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                                   WHERE t.status_bayar = 'Belum Bayar'
                                                   AND DATE_FORMAT(STR_TO_DATE(t.bulan, '%Y-%m'), '%Y-%m-10') < CURDATE()
                                                   ORDER BY keterlambatan DESC";
                                $result_terlambat = mysqli_query($conn, $query_terlambat);
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result_terlambat)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td><strong>" . htmlspecialchars($row['nama']) . "</strong></td>";
                                    echo "<td>" . htmlspecialchars($row['nomor']) . "</td>";
                                    echo "<td>" . date('F Y', strtotime($row['bulan'] . '-01')) . "</td>";
                                    echo "<td>" . date('d/m/Y', strtotime($row['jatuh_tempo'])) . "</td>";
                                    echo "<td><span class='status-badge status-terlambat'>" . $row['keterlambatan'] . " hari</span></td>";
                                    echo "<td><strong>Rp " . number_format($row['jml_tagihan'], 0, ',', '.') . "</strong></td>";
                                    echo "</tr>";
                                }
                                if (mysqli_num_rows($result_terlambat) == 0) {
                                    echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada penghuni yang terlambat bayar</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistik -->
        <section class="mb-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Statistik Kost</h2>
                </div>
            </div>
            <div class="row">
                <?php
                // Total kamar
                $query_total_kamar = "SELECT COUNT(*) as total FROM tb_kamar";
                $result_total_kamar = mysqli_query($conn, $query_total_kamar);
                $total_kamar = mysqli_fetch_assoc($result_total_kamar)['total'];
                
                // Kamar tersedia
                $query_kamar_tersedia = "SELECT COUNT(*) as tersedia FROM tb_kamar k 
                                        LEFT JOIN tb_kmr_penghuni kp ON k.id = kp.id_kamar AND kp.tgl_keluar IS NULL
                                        WHERE kp.id IS NULL";
                $result_kamar_tersedia = mysqli_query($conn, $query_kamar_tersedia);
                $kamar_tersedia = mysqli_fetch_assoc($result_kamar_tersedia)['tersedia'];
                
                // Penghuni aktif
                $query_penghuni_aktif = "SELECT COUNT(*) as aktif FROM tb_penghuni WHERE tgl_keluar IS NULL";
                $result_penghuni_aktif = mysqli_query($conn, $query_penghuni_aktif);
                $penghuni_aktif = mysqli_fetch_assoc($result_penghuni_aktif)['aktif'];
                
                // Total pendapatan
                $query_pendapatan = "SELECT SUM(k.harga) as total_pendapatan 
                                   FROM tb_kamar k 
                                   INNER JOIN tb_kmr_penghuni kp ON k.id = kp.id_kamar AND kp.tgl_keluar IS NULL";
                $result_pendapatan = mysqli_query($conn, $query_pendapatan);
                $total_pendapatan = mysqli_fetch_assoc($result_pendapatan)['total_pendapatan'] ?: 0;
                ?>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-bed fa-3x text-primary mb-3"></i>
                            <h3><?php echo $total_kamar; ?></h3>
                            <p class="card-text">Total Kamar</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-door-open fa-3x text-success mb-3"></i>
                            <h3><?php echo $kamar_tersedia; ?></h3>
                            <p class="card-text">Kamar Tersedia</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h3><?php echo $penghuni_aktif; ?></h3>
                            <p class="card-text">Penghuni Aktif</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-money-bill-wave fa-3x text-warning mb-3"></i>
                            <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                            <p class="card-text">Pendapatan/Bulan</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-home"></i> Kost Manager</h5>
                    <p>Mengelola kost dengan mudah dan efisien</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Kontak</h5>
                    <p>
                        <i class="fas fa-phone"></i> +62 812-3456-7890<br>
                        <i class="fas fa-envelope"></i> info@kostmanager.com<br>
                        <i class="fas fa-map-marker-alt"></i> Jl. Contoh No. 123, Jakarta
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Kost Manager. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 