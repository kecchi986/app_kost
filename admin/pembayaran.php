<?php
session_start();
include '../database/config.php';
include 'auth_check.php';

// Proses tambah pembayaran
if (isset($_POST['tambah'])) {
    $id_tagihan = $_POST['id_tagihan'];
    $jml_bayar = $_POST['jml_bayar'];
    $status = $_POST['status'];
    $tgl_bayar = $_POST['tgl_bayar'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $query = "INSERT INTO tb_bayar (id_tagihan, jml_bayar, status, tgl_bayar, keterangan) 
              VALUES ($id_tagihan, $jml_bayar, '$status', '$tgl_bayar', '$keterangan')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data pembayaran berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: pembayaran.php");
    exit();
}

// Proses update pembayaran
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $jml_bayar = $_POST['jml_bayar'];
    $status = $_POST['status'];
    $tgl_bayar = $_POST['tgl_bayar'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $query = "UPDATE tb_bayar SET jml_bayar=$jml_bayar, status='$status', tgl_bayar='$tgl_bayar', keterangan='$keterangan' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data pembayaran berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: pembayaran.php");
    exit();
}

// Proses hapus pembayaran
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM tb_bayar WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data pembayaran berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: pembayaran.php");
    exit();
}

// Ambil data pembayaran untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM tb_bayar WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran - Admin Panel Kost Manager</title>
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
        .status-cicil {
            background-color: #ffc107;
            color: #000;
        }
        .status-lunas {
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
                        <a class="nav-link" href="barang_bawaan.php">
                            <i class="fas fa-suitcase me-2"></i> Barang Bawaan
                        </a>
                        <a class="nav-link" href="tagihan.php">
                            <i class="fas fa-file-invoice me-2"></i> Tagihan
                        </a>
                        <a class="nav-link active" href="pembayaran.php">
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
                        <h2 class="mb-4"><i class="fas fa-money-bill-wave"></i> Data Pembayaran</h2>
                        <p class="text-muted">Kelola data pembayaran cicilan penghuni kost</p>
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

                <!-- Form Tambah/Edit Pembayaran -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> 
                            <?php echo $edit_data ? 'Edit Data Pembayaran' : 'Tambah Pembayaran Baru'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_tagihan" class="form-label">Tagihan</label>
                                        <select class="form-control" id="id_tagihan" name="id_tagihan" required <?php echo $edit_data ? 'disabled' : ''; ?>>
                                            <option value="">Pilih Tagihan</option>
                                            <?php
                                            $query_tagihan = "SELECT t.*, p.nama as nama_penghuni, k.nomor as nomor_kamar, t.bulan
                                                            FROM tb_tagihan t 
                                                            INNER JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni = kp.id
                                                            INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                                            INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                                            ORDER BY t.bulan DESC, p.nama";
                                            $result_tagihan = mysqli_query($conn, $query_tagihan);
                                            while ($tagihan = mysqli_fetch_assoc($result_tagihan)) {
                                                $selected = ($edit_data && $edit_data['id_tagihan'] == $tagihan['id']) ? 'selected' : '';
                                                echo "<option value='" . $tagihan['id'] . "' $selected>";
                                                echo htmlspecialchars($tagihan['nama_penghuni']) . " - " . $tagihan['nomor_kamar'] . " (" . date('F Y', strtotime($tagihan['bulan'] . '-01')) . ")";
                                                echo " - Rp " . number_format($tagihan['jml_tagihan'], 0, ',', '.');
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                        <?php if ($edit_data): ?>
                                            <input type="hidden" name="id_tagihan" value="<?php echo $edit_data['id_tagihan']; ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jml_bayar" class="form-label">Jumlah Bayar (Rp)</label>
                                        <input type="number" class="form-control" id="jml_bayar" name="jml_bayar" 
                                               value="<?php echo $edit_data ? $edit_data['jml_bayar'] : ''; ?>" 
                                               min="0" step="1000" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="Cicil" <?php echo ($edit_data && $edit_data['status'] == 'Cicil') ? 'selected' : ''; ?>>Cicil</option>
                                            <option value="Lunas" <?php echo ($edit_data && $edit_data['status'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                                        <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" 
                                               value="<?php echo $edit_data ? $edit_data['tgl_bayar'] : date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="1" placeholder="Contoh: Cicilan pertama, Pelunasan"><?php echo $edit_data ? $edit_data['keterangan'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if ($edit_data): ?>
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Data
                                    </button>
                                    <a href="pembayaran.php" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                <?php else: ?>
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Pembayaran
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Pembayaran -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Penghuni</th>
                                        <th>Kamar</th>
                                        <th>Bulan</th>
                                        <th>Total Tagihan</th>
                                        <th>Jumlah Bayar</th>
                                        <th>Status</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT b.*, t.jml_tagihan, t.bulan, p.nama as nama_penghuni, k.nomor as nomor_kamar
                                             FROM tb_bayar b 
                                             INNER JOIN tb_tagihan t ON b.id_tagihan = t.id
                                             INNER JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni = kp.id
                                             INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                             INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                             ORDER BY b.tgl_bayar DESC";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status_class = $row['status'] == 'Lunas' ? 'status-lunas' : 'status-cicil';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nama_penghuni']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($row['nomor_kamar']) . "</td>";
                                        echo "<td>" . date('F Y', strtotime($row['bulan'] . '-01')) . "</td>";
                                        echo "<td>Rp " . number_format($row['jml_tagihan'], 0, ',', '.') . "</td>";
                                        echo "<td><strong>Rp " . number_format($row['jml_bayar'], 0, ',', '.') . "</strong></td>";
                                        echo "<td><span class='status-badge $status_class'>" . $row['status'] . "</span></td>";
                                        echo "<td>" . date('d/m/Y', strtotime($row['tgl_bayar'])) . "</td>";
                                        echo "<td>" . ($row['keterangan'] ? htmlspecialchars($row['keterangan']) : '-') . "</td>";
                                        echo "<td>";
                                        echo "<a href='?edit=" . $row['id'] . "' class='btn btn-sm btn-warning me-1'><i class='fas fa-edit'></i></a>";
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