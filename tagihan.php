<?php
session_start();
include 'database/config.php';

// Proses generate tagihan otomatis
if (isset($_POST['generate'])) {
    $bulan = $_POST['bulan'];
    
    // Ambil semua relasi kamar-penghuni yang aktif
    $query_relasi = "SELECT kp.*, k.harga as harga_kamar, p.nama as nama_penghuni
                     FROM tb_kmr_penghuni kp 
                     INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                     INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                     WHERE kp.tgl_keluar IS NULL";
    $result_relasi = mysqli_query($conn, $query_relasi);
    
    $success_count = 0;
    $error_count = 0;
    
    while ($relasi = mysqli_fetch_assoc($result_relasi)) {
        // Cek apakah tagihan sudah ada untuk bulan ini
        $check_query = "SELECT * FROM tb_tagihan WHERE bulan = '$bulan' AND id_kmr_penghuni = " . $relasi['id'];
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) == 0) {
            // Hitung total barang bawaan
            $query_barang = "SELECT SUM(b.harga) as total_barang
                            FROM tb_brng_bawaan bb 
                            INNER JOIN tb_barang b ON bb.id_barang = b.id
                            WHERE bb.id_penghuni = " . $relasi['id_penghuni'];
            $result_barang = mysqli_query($conn, $query_barang);
            $barang_data = mysqli_fetch_assoc($result_barang);
            $total_barang = $barang_data['total_barang'] ?: 0;
            
            // Total tagihan = harga kamar + total barang bawaan
            $jml_tagihan = $relasi['harga_kamar'] + $total_barang;
            
            $insert_query = "INSERT INTO tb_tagihan (bulan, id_kmr_penghuni, jml_tagihan) 
                           VALUES ('$bulan', " . $relasi['id'] . ", $jml_tagihan)";
            
            if (mysqli_query($conn, $insert_query)) {
                $success_count++;
            } else {
                $error_count++;
            }
        }
    }
    
    if ($success_count > 0) {
        $_SESSION['success'] = "Berhasil generate $success_count tagihan untuk bulan $bulan!";
    }
    if ($error_count > 0) {
        $_SESSION['error'] = "Gagal generate $error_count tagihan!";
    }
    
    header("Location: tagihan.php");
    exit();
}

// Proses update status pembayaran
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $tgl_bayar = $status == 'Sudah Bayar' ? date('Y-m-d') : 'NULL';
    
    $query = "UPDATE tb_tagihan SET status_bayar='$status'";
    if ($tgl_bayar != 'NULL') {
        $query .= ", tgl_bayar='$tgl_bayar'";
    } else {
        $query .= ", tgl_bayar=NULL";
    }
    $query .= " WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Status pembayaran berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: tagihan.php");
    exit();
}

// Proses hapus tagihan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM tb_tagihan WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data tagihan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: tagihan.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tagihan - Sistem Manajemen Kost</title>
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
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-belum {
            background-color: #ffc107;
            color: #000;
        }
        .status-sudah {
            background-color: #28a745;
            color: #fff;
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
                        <a class="nav-link" href="barang.php">
                            <i class="fas fa-box me-2"></i> Data Barang
                        </a>
                        <a class="nav-link" href="relasi_kamar.php">
                            <i class="fas fa-link me-2"></i> Relasi Kamar
                        </a>
                        <a class="nav-link" href="barang_bawaan.php">
                            <i class="fas fa-suitcase me-2"></i> Barang Bawaan
                        </a>
                        <a class="nav-link active" href="tagihan.php">
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
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4"><i class="fas fa-file-invoice"></i> Data Tagihan</h2>
                        <p class="text-muted">Kelola tagihan bulanan penghuni kost</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Generate Tagihan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> Generate Tagihan Otomatis
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bulan" class="form-label">Bulan Tagihan</label>
                                        <input type="month" class="form-control" id="bulan" name="bulan" 
                                               value="<?php echo date('Y-m'); ?>" required>
                                        <small class="form-text text-muted">Tagihan akan dibuat untuk semua penghuni aktif</small>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" name="generate" class="btn btn-primary">
                                        <i class="fas fa-magic"></i> Generate Tagihan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Tagihan -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Tagihan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Penghuni</th>
                                        <th>Kamar</th>
                                        <th>Jumlah Tagihan</th>
                                        <th>Status</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT t.*, p.nama as nama_penghuni, k.nomor as nomor_kamar, k.harga as harga_kamar
                                             FROM tb_tagihan t 
                                             INNER JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni = kp.id
                                             INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                             INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                             ORDER BY t.bulan DESC, p.nama";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status_class = $row['status_bayar'] == 'Sudah Bayar' ? 'status-sudah' : 'status-belum';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . date('F Y', strtotime($row['bulan'] . '-01')) . "</strong></td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nama_penghuni']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($row['nomor_kamar']) . "</td>";
                                        echo "<td><strong>Rp " . number_format($row['jml_tagihan'], 0, ',', '.') . "</strong><br>";
                                        echo "<small class='text-muted'>Sewa: Rp " . number_format($row['harga_kamar'], 0, ',', '.') . "</small></td>";
                                        echo "<td><span class='status-badge $status_class'>" . $row['status_bayar'] . "</span></td>";
                                        echo "<td>" . ($row['tgl_bayar'] ? date('d/m/Y', strtotime($row['tgl_bayar'])) : '-') . "</td>";
                                        echo "<td>";
                                        if ($row['status_bayar'] == 'Belum Bayar') {
                                            echo "<button class='btn btn-sm btn-success me-1' onclick='updateStatus(" . $row['id'] . ", \"Sudah Bayar\")'><i class='fas fa-check'></i> Bayar</button>";
                                        } else {
                                            echo "<button class='btn btn-sm btn-warning me-1' onclick='updateStatus(" . $row['id'] . ", \"Belum Bayar\")'><i class='fas fa-undo'></i> Batal</button>";
                                        }
                                        echo "<a href='?hapus=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus tagihan ini?\")'><i class='fas fa-trash'></i></a>";
                                        echo "</td>";
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

    <!-- Modal Update Status -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="tagihan_id" name="id">
                        <input type="hidden" id="tagihan_status" name="status">
                        <p>Apakah Anda yakin ingin mengubah status pembayaran tagihan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateStatus(id, status) {
            document.getElementById('tagihan_id').value = id;
            document.getElementById('tagihan_status').value = status;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }
    </script>
</body>
</html> 