<?php
session_start();
include '../database/config.php';
include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kost Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white"><i class="fas fa-user-shield"></i> Admin Panel</h4>
                        <small class="text-white-50">Kost Manager</small>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="penghuni.php">
                            <i class="fas fa-users me-2"></i> Data Penghuni
                        </a>
                        <a class="nav-link" href="kamar.php">
                            <i class="fas fa-bed me-2"></i> Data Kamar
                        </a>
                        <a class="nav-link" href="barang.php">
                            <i class="fas fa-box me-2"></i> Data Barang
                        </a>
                        <a class="nav-link" href="relasi_kamar.php">
                            <i class="fas fa-link me-2"></i> Relasi Kamar
                        </a>
                        <a class="nav-link" href="barang_bawaan.php">
                            <i class="fas fa-suitcase me-2"></i> Barang Bawaan
                        </a>
                        <a class="nav-link" href="tagihan.php">
                            <i class="fas fa-file-invoice me-2"></i> Tagihan
                        </a>
                        <a class="nav-link" href="pembayaran.php">
                            <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
                        </a>
                        <a class="nav-link" href="pindah_kamar.php">
                            <i class="fas fa-exchange-alt me-2"></i> Pindah Kamar
                        </a>
                        <a class="nav-link" href="laporan.php">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a>
                        <?php if ($_SESSION['admin_level'] === 'super_admin'): ?>
                        <a class="nav-link" href="manage_admin.php">
                            <i class="fas fa-users-cog me-2"></i> Kelola Admin
                        </a>
                        <?php endif; ?>
                        <hr class="text-white-50">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-2"></i> Halaman Depan
                        </a>
                        <hr class="text-white-50">
                        <div class="text-white-50 small mb-2">
                            <i class="fas fa-user me-2"></i> <?php echo htmlspecialchars($_SESSION['admin_nama']); ?>
                        </div>
                        <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h2>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <?php
                    // Total kamar
                    $query_kamar = "SELECT COUNT(*) as total_kamar FROM tb_kamar";
                    $result_kamar = mysqli_query($conn, $query_kamar);
                    $total_kamar = mysqli_fetch_assoc($result_kamar)['total_kamar'];
                    
                    // Total penghuni aktif
                    $query_penghuni_aktif = "SELECT COUNT(*) as total_aktif FROM tb_penghuni WHERE tgl_keluar IS NULL";
                    $result_penghuni_aktif = mysqli_query($conn, $query_penghuni_aktif);
                    $total_aktif = mysqli_fetch_assoc($result_penghuni_aktif)['total_aktif'];
                    
                    // Kamar tersedia
                    $kamar_tersedia = $total_kamar - $total_aktif;
                    
                    // Total penghuni (semua)
                    $query_penghuni_total = "SELECT COUNT(*) as total_penghuni FROM tb_penghuni";
                    $result_penghuni_total = mysqli_query($conn, $query_penghuni_total);
                    $total_penghuni = mysqli_fetch_assoc($result_penghuni_total)['total_penghuni'];
                    
                    // Tagihan belum bayar
                    $query_tagihan_belum = "SELECT COUNT(*) as total_belum FROM tb_tagihan WHERE status_bayar = 'Belum Bayar'";
                    $result_tagihan_belum = mysqli_query($conn, $query_tagihan_belum);
                    $total_belum_bayar = mysqli_fetch_assoc($result_tagihan_belum)['total_belum'];
                    ?>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3><?php echo $total_kamar; ?></h3>
                                    <p class="mb-0">Total Kamar</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-bed fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3><?php echo $total_aktif; ?></h3>
                                    <p class="mb-0">Penghuni Aktif</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3><?php echo $kamar_tersedia; ?></h3>
                                    <p class="mb-0">Kamar Tersedia</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-door-open fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3><?php echo $total_belum_bayar; ?></h3>
                                    <p class="mb-0">Tagihan Belum Bayar</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kamar Tersedia -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bed"></i> Status Kamar</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $query_kamar_status = "SELECT k.*, 
                                                          CASE WHEN kp.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                                                          FROM tb_kamar k 
                                                          LEFT JOIN tb_kmr_penghuni kp ON k.id = kp.id_kamar AND kp.tgl_keluar IS NULL
                                                          ORDER BY k.nomor";
                                    $result_kamar_status = mysqli_query($conn, $query_kamar_status);
                                    while ($kamar = mysqli_fetch_assoc($result_kamar_status)) {
                                        $status_class = $kamar['status'] == 'Tersedia' ? 'status-tersedia' : 'status-terisi';
                                        $card_class = $kamar['status'] == 'Tersedia' ? 'border-success' : 'border-danger';
                                        ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 <?php echo $card_class; ?>">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">
                                                        <i class="fas fa-door-open"></i> Kamar <?php echo htmlspecialchars($kamar['nomor']); ?>
                                                    </h6>
                                                    <h5 class="text-primary mb-2">Rp <?php echo number_format($kamar['harga'], 0, ',', '.'); ?></h5>
                                                    <span class="status-badge <?php echo $status_class; ?>">
                                                        <?php echo $kamar['status']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock"></i> Sebentar Lagi Bayar</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kamar</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Total</th>
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
                                                                  ORDER BY jatuh_tempo
                                                                  LIMIT 5";
                                            $result_segera_bayar = mysqli_query($conn, $query_segera_bayar);
                                            while ($row = mysqli_fetch_assoc($result_segera_bayar)) {
                                                $days_left = ceil((strtotime($row['jatuh_tempo']) - time()) / (60 * 60 * 24));
                                                echo "<tr>";
                                                echo "<td><strong>" . htmlspecialchars($row['nama']) . "</strong></td>";
                                                echo "<td>" . htmlspecialchars($row['nomor']) . "</td>";
                                                echo "<td><span class='status-badge status-segera-bayar'>" . date('d/m/Y', strtotime($row['jatuh_tempo'])) . " ($days_left hari)</span></td>";
                                                echo "<td><strong>Rp " . number_format($row['total_tagihan'], 0, ',', '.') . "</strong></td>";
                                                echo "</tr>";
                                            }
                                            if (mysqli_num_rows($result_segera_bayar) == 0) {
                                                echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada yang sebentar lagi jatuh tempo</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Terlambat Bayar</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kamar</th>
                                                <th>Keterlambatan</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_terlambat = "SELECT p.nama, k.nomor, 
                                                               DATEDIFF(CURDATE(), DATE_FORMAT(STR_TO_DATE(t.bulan, '%Y-%m'), '%Y-%m-10')) as keterlambatan,
                                                               t.jml_tagihan
                                                               FROM tb_tagihan t 
                                                               INNER JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni = kp.id
                                                               INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                                               INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                                               WHERE t.status_bayar = 'Belum Bayar'
                                                               AND DATE_FORMAT(STR_TO_DATE(t.bulan, '%Y-%m'), '%Y-%m-10') < CURDATE()
                                                               ORDER BY keterlambatan DESC
                                                               LIMIT 5";
                                            $result_terlambat = mysqli_query($conn, $query_terlambat);
                                            while ($row = mysqli_fetch_assoc($result_terlambat)) {
                                                echo "<tr>";
                                                echo "<td><strong>" . htmlspecialchars($row['nama']) . "</strong></td>";
                                                echo "<td>" . htmlspecialchars($row['nomor']) . "</td>";
                                                echo "<td><span class='status-badge status-terlambat'>" . $row['keterlambatan'] . " hari</span></td>";
                                                echo "<td><strong>Rp " . number_format($row['jml_tagihan'], 0, ',', '.') . "</strong></td>";
                                                echo "</tr>";
                                            }
                                            if (mysqli_num_rows($result_terlambat) == 0) {
                                                echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada yang terlambat bayar</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="penghuni.php" class="btn btn-primary w-100">
                                            <i class="fas fa-user-plus"></i><br>
                                            Tambah Penghuni
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="tagihan.php" class="btn btn-success w-100">
                                            <i class="fas fa-file-invoice"></i><br>
                                            Generate Tagihan
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="pembayaran.php" class="btn btn-warning w-100">
                                            <i class="fas fa-money-bill-wave"></i><br>
                                            Input Pembayaran
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="laporan.php" class="btn btn-info w-100">
                                            <i class="fas fa-chart-bar"></i><br>
                                            Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 