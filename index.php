<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Kost</title>
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
                        <a class="nav-link" href="laporan.php">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <?php
                    include 'database/config.php';
                    
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
                                    <h3><?php echo $total_penghuni; ?></h3>
                                    <p class="mb-0">Total Penghuni</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-friends fa-2x"></i>
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
                                <h5 class="mb-0"><i class="fas fa-users"></i> Penghuni Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kamar</th>
                                                <th>Tanggal Masuk</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_recent = "SELECT p.nama, k.nomor, p.tgl_masuk 
                                                            FROM tb_penghuni p 
                                                            LEFT JOIN tb_kamar k ON p.kamar_id = k.id 
                                                            WHERE p.tgl_keluar IS NULL 
                                                            ORDER BY p.tgl_masuk DESC 
                                                            LIMIT 5";
                                            $result_recent = mysqli_query($conn, $query_recent);
                                            while ($row = mysqli_fetch_assoc($result_recent)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nomor']) . "</td>";
                                                echo "<td>" . date('d/m/Y', strtotime($row['tgl_masuk'])) . "</td>";
                                                echo "</tr>";
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
                                <h5 class="mb-0"><i class="fas fa-bed"></i> Status Kamar</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nomor Kamar</th>
                                                <th>Harga</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_kamar_status = "SELECT k.nomor, k.harga, 
                                                                  CASE WHEN p.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                                                                  FROM tb_kamar k 
                                                                  LEFT JOIN tb_penghuni p ON k.id = p.kamar_id AND p.tgl_keluar IS NULL
                                                                  ORDER BY k.nomor";
                                            $result_kamar_status = mysqli_query($conn, $query_kamar_status);
                                            while ($row = mysqli_fetch_assoc($result_kamar_status)) {
                                                $status_class = $row['status'] == 'Tersedia' ? 'text-success' : 'text-danger';
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 