<?php
session_start();
include 'database/config.php';

// Proses tambah kamar
if (isset($_POST['tambah'])) {
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor']);
    $harga = $_POST['harga'];
    
    $query = "INSERT INTO tb_kamar (nomor, harga) VALUES ('$nomor', $harga)";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data kamar berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: kamar.php");
    exit();
}

// Proses update kamar
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor']);
    $harga = $_POST['harga'];
    
    $query = "UPDATE tb_kamar SET nomor='$nomor', harga=$harga WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data kamar berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: kamar.php");
    exit();
}

// Proses hapus kamar
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM tb_kamar WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data kamar berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: kamar.php");
    exit();
}

// Ambil data kamar untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM tb_kamar WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kamar - Sistem Manajemen Kost</title>
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
                        <a class="nav-link active" href="kamar.php">
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
                        <h2 class="mb-4"><i class="fas fa-bed"></i> Data Kamar</h2>
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

                <!-- Form Tambah/Edit Kamar -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> 
                            <?php echo $edit_data ? 'Edit Data Kamar' : 'Tambah Kamar Baru'; ?>
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
                                        <label for="nomor" class="form-label">Nomor Kamar</label>
                                        <input type="text" class="form-control" id="nomor" name="nomor" 
                                               value="<?php echo $edit_data ? $edit_data['nomor'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga Sewa (Rp)</label>
                                        <input type="number" class="form-control" id="harga" name="harga" 
                                               value="<?php echo $edit_data ? $edit_data['harga'] : ''; ?>" 
                                               min="0" step="1000" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if ($edit_data): ?>
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Data
                                    </button>
                                    <a href="kamar.php" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                <?php else: ?>
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Kamar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Kamar -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Kamar</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Kamar</th>
                                        <th>Harga Sewa</th>
                                        <th>Status</th>
                                        <th>Penghuni</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT k.*, 
                                             CASE WHEN p.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status,
                                             p.nama as nama_penghuni
                                             FROM tb_kamar k 
                                             LEFT JOIN tb_penghuni p ON k.id = p.kamar_id AND p.tgl_keluar IS NULL
                                             ORDER BY k.nomor";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status_class = $row['status'] == 'Tersedia' ? 'text-success' : 'text-danger';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nomor']) . "</strong></td>";
                                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                        echo "<td><span class='$status_class'>" . $row['status'] . "</span></td>";
                                        echo "<td>" . ($row['nama_penghuni'] ? htmlspecialchars($row['nama_penghuni']) : '-') . "</td>";
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