<?php
session_start();
include 'database/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Sistem Manajemen Kost</title>
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
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
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
                        <h4 class="text-white"><i class="fas fa-home"></i> Kost Manager</h4>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="penghuni.php">
                            <i class="fas fa-users me-2"></i> Data Penghuni
                        </a>
                        <a class="nav-link" href="kamar.php">
                            <i class="fas fa-bed me-2"></i> Data Kamar
                        </a>
                        <a class="nav-link active" href="laporan.php">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Laporan Kost</h2>
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
                    
                    // Total pendapatan potensial
                    $query_pendapatan = "SELECT SUM(k.harga) as total_pendapatan 
                                       FROM tb_kamar k 
                                       INNER JOIN tb_penghuni p ON k.id = p.kamar_id 
                                       WHERE p.tgl_keluar IS NULL";
                    $result_pendapatan = mysqli_query($conn, $query_pendapatan);
                    $total_pendapatan = mysqli_fetch_assoc($result_pendapatan)['total_pendapatan'] ?: 0;
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
                                    <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                                    <p class="mb-0">Pendapatan/Bulan</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Detail -->
                <div class="row">
                    <!-- Laporan Penghuni -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-users"></i> Laporan Penghuni</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kamar</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_penghuni = "SELECT p.nama, k.nomor, p.tgl_masuk, p.tgl_keluar,
                                                              CASE WHEN p.tgl_keluar IS NULL THEN 'Aktif' ELSE 'Keluar' END as status
                                                              FROM tb_penghuni p 
                                                              LEFT JOIN tb_kamar k ON p.kamar_id = k.id 
                                                              ORDER BY p.tgl_masuk DESC";
                                            $result_penghuni = mysqli_query($conn, $query_penghuni);
                                            while ($row = mysqli_fetch_assoc($result_penghuni)) {
                                                $status_class = $row['status'] == 'Aktif' ? 'text-success' : 'text-danger';
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                                echo "<td>" . ($row['nomor'] ? $row['nomor'] : '-') . "</td>";
                                                echo "<td>" . date('d/m/Y', strtotime($row['tgl_masuk'])) . "</td>";
                                                echo "<td><span class='$status_class'>" . $row['status'] . "</span></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Laporan Kamar -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bed"></i> Laporan Kamar</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nomor Kamar</th>
                                                <th>Harga</th>
                                                <th>Status</th>
                                                <th>Penghuni</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_kamar_detail = "SELECT k.nomor, k.harga, 
                                                                  CASE WHEN p.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status,
                                                                  p.nama as nama_penghuni
                                                                  FROM tb_kamar k 
                                                                  LEFT JOIN tb_penghuni p ON k.id = p.kamar_id AND p.tgl_keluar IS NULL
                                                                  ORDER BY k.nomor";
                                            $result_kamar_detail = mysqli_query($conn, $query_kamar_detail);
                                            while ($row = mysqli_fetch_assoc($result_kamar_detail)) {
                                                $status_class = $row['status'] == 'Tersedia' ? 'text-success' : 'text-danger';
                                                echo "<tr>";
                                                echo "<td><strong>" . htmlspecialchars($row['nomor']) . "</strong></td>";
                                                echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                                echo "<td><span class='$status_class'>" . $row['status'] . "</span></td>";
                                                echo "<td>" . ($row['nama_penghuni'] ? htmlspecialchars($row['nama_penghuni']) : '-') . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Keuangan -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Laporan Keuangan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Pendapatan per Kamar (Bulanan)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Kamar</th>
                                                        <th>Harga</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query_keuangan = "SELECT k.nomor, k.harga, 
                                                                      CASE WHEN p.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                                                                      FROM tb_kamar k 
                                                                      LEFT JOIN tb_penghuni p ON k.id = p.kamar_id AND p.tgl_keluar IS NULL
                                                                      ORDER BY k.nomor";
                                                    $result_keuangan = mysqli_query($conn, $query_keuangan);
                                                    $total_pendapatan_terisi = 0;
                                                    while ($row = mysqli_fetch_assoc($result_keuangan)) {
                                                        $status_class = $row['status'] == 'Tersedia' ? 'text-danger' : 'text-success';
                                                        if ($row['status'] == 'Terisi') {
                                                            $total_pendapatan_terisi += $row['harga'];
                                                        }
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row['nomor']) . "</td>";
                                                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                                        echo "<td><span class='$status_class'>" . $row['status'] . "</span></td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="stats-card">
                                                    <h4>Total Pendapatan Aktif</h4>
                                                    <h2>Rp <?php echo number_format($total_pendapatan_terisi, 0, ',', '.'); ?></h2>
                                                    <p class="mb-0">per bulan</p>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="stats-card">
                                                    <h4>Total Pendapatan Potensial</h4>
                                                    <h2>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h2>
                                                    <p class="mb-0">jika semua kamar terisi</p>
                                                </div>
                                            </div>
                                        </div>
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