<?php
session_start();
include 'database/config.php';

// Proses tambah relasi
if (isset($_POST['tambah'])) {
    $id_kamar = $_POST['id_kamar'];
    $id_penghuni = $_POST['id_penghuni'];
    $tgl_masuk = $_POST['tgl_masuk'];
    $tgl_keluar = $_POST['tgl_keluar'] ? $_POST['tgl_keluar'] : 'NULL';
    
    // Cek apakah kamar sudah terisi
    $check_query = "SELECT * FROM tb_kmr_penghuni WHERE id_kamar = $id_kamar AND tgl_keluar IS NULL";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "Kamar sudah terisi!";
        header("Location: relasi_kamar.php");
        exit();
    }
    
    $query = "INSERT INTO tb_kmr_penghuni (id_kamar, id_penghuni, tgl_masuk";
    if ($tgl_keluar != 'NULL') {
        $query .= ", tgl_keluar) VALUES ($id_kamar, $id_penghuni, '$tgl_masuk', '$tgl_keluar')";
    } else {
        $query .= ") VALUES ($id_kamar, $id_penghuni, '$tgl_masuk')";
    }
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data relasi kamar-penghuni berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: relasi_kamar.php");
    exit();
}

// Proses update relasi
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_kamar = $_POST['id_kamar'];
    $id_penghuni = $_POST['id_penghuni'];
    $tgl_masuk = $_POST['tgl_masuk'];
    $tgl_keluar = $_POST['tgl_keluar'] ? $_POST['tgl_keluar'] : 'NULL';
    
    $query = "UPDATE tb_kmr_penghuni SET id_kamar=$id_kamar, id_penghuni=$id_penghuni, tgl_masuk='$tgl_masuk'";
    if ($tgl_keluar != 'NULL') {
        $query .= ", tgl_keluar='$tgl_keluar'";
    } else {
        $query .= ", tgl_keluar=NULL";
    }
    $query .= " WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data relasi kamar-penghuni berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: relasi_kamar.php");
    exit();
}

// Proses hapus relasi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM tb_kmr_penghuni WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data relasi kamar-penghuni berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: relasi_kamar.php");
    exit();
}

// Ambil data relasi untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM tb_kmr_penghuni WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relasi Kamar-Penghuni - Sistem Manajemen Kost</title>
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
                        <a class="nav-link active" href="relasi_kamar.php">
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
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4"><i class="fas fa-link"></i> Relasi Kamar-Penghuni</h2>
                        <p class="text-muted">Kelola data penghuni yang menempati kamar tertentu</p>
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

                <!-- Form Tambah/Edit Relasi -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> 
                            <?php echo $edit_data ? 'Edit Relasi Kamar-Penghuni' : 'Tambah Relasi Baru'; ?>
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
                                        <label for="id_kamar" class="form-label">Kamar</label>
                                        <select class="form-control" id="id_kamar" name="id_kamar" required>
                                            <option value="">Pilih Kamar</option>
                                            <?php
                                            $query_kamar = "SELECT k.*, 
                                                           CASE WHEN kp.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                                                           FROM tb_kamar k 
                                                           LEFT JOIN tb_kmr_penghuni kp ON k.id = kp.id_kamar AND kp.tgl_keluar IS NULL
                                                           ORDER BY k.nomor";
                                            $result_kamar = mysqli_query($conn, $query_kamar);
                                            while ($kamar = mysqli_fetch_assoc($result_kamar)) {
                                                $selected = ($edit_data && $edit_data['id_kamar'] == $kamar['id']) ? 'selected' : '';
                                                $disabled = ($kamar['status'] == 'Terisi' && !$selected) ? 'disabled' : '';
                                                echo "<option value='" . $kamar['id'] . "' $selected $disabled>";
                                                echo $kamar['nomor'] . " - Rp " . number_format($kamar['harga'], 0, ',', '.') . " ($status)";
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_penghuni" class="form-label">Penghuni</label>
                                        <select class="form-control" id="id_penghuni" name="id_penghuni" required>
                                            <option value="">Pilih Penghuni</option>
                                            <?php
                                            $query_penghuni = "SELECT * FROM tb_penghuni ORDER BY nama";
                                            $result_penghuni = mysqli_query($conn, $query_penghuni);
                                            while ($penghuni = mysqli_fetch_assoc($result_penghuni)) {
                                                $selected = ($edit_data && $edit_data['id_penghuni'] == $penghuni['id']) ? 'selected' : '';
                                                echo "<option value='" . $penghuni['id'] . "' $selected>";
                                                echo htmlspecialchars($penghuni['nama']) . " - " . $penghuni['no_ktp'];
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" 
                                               value="<?php echo $edit_data ? $edit_data['tgl_masuk'] : date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tgl_keluar" class="form-label">Tanggal Keluar</label>
                                        <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar" 
                                               value="<?php echo $edit_data ? $edit_data['tgl_keluar'] : ''; ?>">
                                        <small class="form-text text-muted">Kosongkan jika penghuni masih aktif</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if ($edit_data): ?>
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Data
                                    </button>
                                    <a href="relasi_kamar.php" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                <?php else: ?>
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Relasi
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Relasi -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Relasi Kamar-Penghuni</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kamar</th>
                                        <th>Penghuni</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT kp.*, k.nomor as nomor_kamar, k.harga, p.nama as nama_penghuni, p.no_ktp
                                             FROM tb_kmr_penghuni kp 
                                             INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                             INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                             ORDER BY kp.tgl_masuk DESC";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status = $row['tgl_keluar'] ? 'Keluar' : 'Aktif';
                                        $status_class = $status == 'Aktif' ? 'text-success' : 'text-danger';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nomor_kamar']) . "</strong><br>";
                                        echo "<small class='text-muted'>Rp " . number_format($row['harga'], 0, ',', '.') . "</small></td>";
                                        echo "<td>" . htmlspecialchars($row['nama_penghuni']) . "<br>";
                                        echo "<small class='text-muted'>" . $row['no_ktp'] . "</small></td>";
                                        echo "<td>" . date('d/m/Y', strtotime($row['tgl_masuk'])) . "</td>";
                                        echo "<td>" . ($row['tgl_keluar'] ? date('d/m/Y', strtotime($row['tgl_keluar'])) : '-') . "</td>";
                                        echo "<td><span class='$status_class'>$status</span></td>";
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