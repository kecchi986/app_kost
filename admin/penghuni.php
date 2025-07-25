<?php
session_start();
include '../database/config.php';
include 'auth_check.php';

// Proses tambah penghuni
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $tgl_masuk = $_POST['tgl_masuk'];
    $kamar_id = $_POST['kamar_id'];
    
    $query = "INSERT INTO tb_penghuni (nama, no_ktp, no_hp, tgl_masuk, kamar_id) VALUES ('$nama', '$no_ktp', '$no_hp', '$tgl_masuk', $kamar_id)";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data penghuni berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: penghuni.php");
    exit();
}

// Proses update penghuni
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $tgl_masuk = $_POST['tgl_masuk'];
    $tgl_keluar = $_POST['tgl_keluar'] ? $_POST['tgl_keluar'] : 'NULL';
    $kamar_id = $_POST['kamar_id'] ? $_POST['kamar_id'] : 'NULL';
    
    $query = "UPDATE tb_penghuni SET nama='$nama', no_ktp='$no_ktp', no_hp='$no_hp', tgl_masuk='$tgl_masuk'";
    if ($tgl_keluar != 'NULL') {
        $query .= ", tgl_keluar='$tgl_keluar'";
    }
    if ($kamar_id != 'NULL') {
        $query .= ", kamar_id=$kamar_id";
    } else {
        $query .= ", kamar_id=NULL";
    }
    $query .= " WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data penghuni berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: penghuni.php");
    exit();
}

// Proses hapus penghuni
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM tb_penghuni WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data penghuni berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: penghuni.php");
    exit();
}

// Ambil data penghuni untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM tb_penghuni WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penghuni - Admin Panel Kost Manager</title>
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
                        <a class="nav-link active" href="penghuni.php">
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
                        <h2 class="mb-4"><i class="fas fa-users"></i> Data Penghuni</h2>
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

                <!-- Form Tambah/Edit Penghuni -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> 
                            <?php echo $edit_data ? 'Edit Data Penghuni' : 'Tambah Penghuni Baru'; ?>
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
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_ktp" class="form-label">Nomor KTP</label>
                                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" 
                                               value="<?php echo $edit_data ? $edit_data['no_ktp'] : ''; ?>" 
                                               maxlength="16" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">Nomor Handphone</label>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                               value="<?php echo $edit_data ? $edit_data['no_hp'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kamar_id" class="form-label">Kamar</label>
                                        <select class="form-control" id="kamar_id" name="kamar_id" required>
                                            <option value="">Pilih Kamar</option>
                                            <?php
                                            $query_kamar = "SELECT k.*, 
                                                           CASE WHEN p.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                                                           FROM tb_kamar k 
                                                           LEFT JOIN tb_penghuni p ON k.id = p.kamar_id AND p.tgl_keluar IS NULL
                                                           ORDER BY k.nomor";
                                            $result_kamar = mysqli_query($conn, $query_kamar);
                                            while ($kamar = mysqli_fetch_assoc($result_kamar)) {
                                                $selected = ($edit_data && $edit_data['kamar_id'] == $kamar['id']) ? 'selected' : '';
                                                $disabled = ($kamar['status'] == 'Terisi' && !$selected) ? 'disabled' : '';
                                                echo "<option value='" . $kamar['id'] . "' $selected $disabled>";
                                                echo $kamar['nomor'] . " - Rp " . number_format($kamar['harga'], 0, ',', '.') . " ($status)";
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
                                    <a href="penghuni.php" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                <?php else: ?>
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Penghuni
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Penghuni -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Penghuni</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No KTP</th>
                                        <th>No HP</th>
                                        <th>Kamar</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT p.*, k.nomor as nomor_kamar 
                                             FROM tb_penghuni p 
                                             LEFT JOIN tb_kamar k ON p.kamar_id = k.id 
                                             ORDER BY p.tgl_masuk DESC";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status = $row['tgl_keluar'] ? 'Keluar' : 'Aktif';
                                        $status_class = $status == 'Aktif' ? 'text-success' : 'text-danger';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['no_ktp']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                                        echo "<td>" . ($row['nomor_kamar'] ? $row['nomor_kamar'] : '-') . "</td>";
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