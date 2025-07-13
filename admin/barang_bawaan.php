<?php
session_start();
include '../database/config.php';
include 'auth_check.php';

// Proses tambah barang bawaan
if (isset($_POST['tambah'])) {
    $id_penghuni = $_POST['id_penghuni'];
    $id_barang = $_POST['id_barang'];
    
    // Cek apakah sudah ada barang yang sama untuk penghuni ini
    $check_query = "SELECT * FROM tb_brng_bawaan WHERE id_penghuni = $id_penghuni AND id_barang = $id_barang";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "Barang ini sudah ada untuk penghuni tersebut!";
        header("Location: barang_bawaan.php");
        exit();
    }
    
    $query = "INSERT INTO tb_brng_bawaan (id_penghuni, id_barang) VALUES ($id_penghuni, $id_barang)";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data barang bawaan berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: barang_bawaan.php");
    exit();
}

// Proses hapus barang bawaan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM tb_brng_bawaan WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data barang bawaan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: barang_bawaan.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang Bawaan - Admin Panel Kost Manager</title>
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
                        <a class="nav-link active" href="barang_bawaan.php">
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
                        <h2 class="mb-4"><i class="fas fa-suitcase"></i> Data Barang Bawaan</h2>
                        <p class="text-muted">Kelola data barang yang dibawa/digunakan oleh penghuni</p>
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

                <!-- Form Tambah Barang Bawaan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> Tambah Barang Bawaan Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_penghuni" class="form-label">Penghuni</label>
                                        <select class="form-control" id="id_penghuni" name="id_penghuni" required>
                                            <option value="">Pilih Penghuni</option>
                                            <?php
                                            $query_penghuni = "SELECT * FROM tb_penghuni WHERE tgl_keluar IS NULL ORDER BY nama";
                                            $result_penghuni = mysqli_query($conn, $query_penghuni);
                                            while ($penghuni = mysqli_fetch_assoc($result_penghuni)) {
                                                echo "<option value='" . $penghuni['id'] . "'>";
                                                echo htmlspecialchars($penghuni['nama']) . " - " . $penghuni['no_ktp'];
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_barang" class="form-label">Barang</label>
                                        <select class="form-control" id="id_barang" name="id_barang" required>
                                            <option value="">Pilih Barang</option>
                                            <?php
                                            $query_barang = "SELECT * FROM tb_barang ORDER BY nama";
                                            $result_barang = mysqli_query($conn, $query_barang);
                                            while ($barang = mysqli_fetch_assoc($result_barang)) {
                                                echo "<option value='" . $barang['id'] . "'>";
                                                echo htmlspecialchars($barang['nama']) . " - Rp " . number_format($barang['harga'], 0, ',', '.');
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="tambah" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Barang Bawaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Barang Bawaan -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Barang Bawaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Penghuni</th>
                                        <th>Barang</th>
                                        <th>Harga</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT bb.*, p.nama as nama_penghuni, p.no_ktp, b.nama as nama_barang, b.harga
                                             FROM tb_brng_bawaan bb 
                                             INNER JOIN tb_penghuni p ON bb.id_penghuni = p.id
                                             INNER JOIN tb_barang b ON bb.id_barang = b.id
                                             ORDER BY p.nama, b.nama";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nama_penghuni']) . "</strong><br>";
                                        echo "<small class='text-muted'>" . $row['no_ktp'] . "</small></td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nama_barang']) . "</strong></td>";
                                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                        echo "<td>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>";
                                        echo "<td>";
                                        echo "<a href='?hapus=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'><i class='fas fa-trash'></i></a>";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 