<?php
session_start();
include 'database/config.php';

// Proses pindah kamar
if (isset($_POST['pindah_kamar'])) {
    $id_kmr_penghuni = $_POST['id_kmr_penghuni'];
    $id_kamar_baru = $_POST['id_kamar_baru'];
    $tgl_pindah = $_POST['tgl_pindah'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    // Ambil data relasi kamar lama
    $query_relasi = "SELECT * FROM tb_kmr_penghuni WHERE id = $id_kmr_penghuni";
    $result_relasi = mysqli_query($conn, $query_relasi);
    $relasi_lama = mysqli_fetch_assoc($result_relasi);
    
    // Update tgl_keluar di relasi lama
    $update_lama = "UPDATE tb_kmr_penghuni SET tgl_keluar = '$tgl_pindah' WHERE id = $id_kmr_penghuni";
    mysqli_query($conn, $update_lama);
    
    // Buat relasi baru
    $insert_baru = "INSERT INTO tb_kmr_penghuni (id_kamar, id_penghuni, tgl_masuk) 
                    VALUES ($id_kamar_baru, " . $relasi_lama['id_penghuni'] . ", '$tgl_pindah')";
    mysqli_query($conn, $insert_baru);
    
    // Update kamar_id di tabel penghuni
    $update_penghuni = "UPDATE tb_penghuni SET kamar_id = $id_kamar_baru WHERE id = " . $relasi_lama['id_penghuni'];
    mysqli_query($conn, $update_penghuni);
    
    $_SESSION['success'] = "Data pindah kamar berhasil disimpan!";
    header("Location: pindah_kamar.php");
    exit();
}

// Proses keluar kost
if (isset($_POST['keluar_kost'])) {
    $id_penghuni = $_POST['id_penghuni'];
    $tgl_keluar = $_POST['tgl_keluar'];
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    
    // Update tgl_keluar di tabel penghuni
    $update_penghuni = "UPDATE tb_penghuni SET tgl_keluar = '$tgl_keluar' WHERE id = $id_penghuni";
    mysqli_query($conn, $update_penghuni);
    
    // Update tgl_keluar di relasi kamar-penghuni yang aktif
    $update_relasi = "UPDATE tb_kmr_penghuni SET tgl_keluar = '$tgl_keluar' 
                      WHERE id_penghuni = $id_penghuni AND tgl_keluar IS NULL";
    mysqli_query($conn, $update_relasi);
    
    // Hapus barang bawaan penghuni
    $hapus_barang = "DELETE FROM tb_brng_bawaan WHERE id_penghuni = $id_penghuni";
    mysqli_query($conn, $hapus_barang);
    
    $_SESSION['success'] = "Data keluar kost berhasil disimpan!";
    header("Location: pindah_kamar.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pindah Kamar & Keluar Kost - Sistem Manajemen Kost</title>
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
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
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
                        <a class="nav-link active" href="pindah_kamar.php">
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
                        <h2 class="mb-4"><i class="fas fa-exchange-alt"></i> Pindah Kamar & Keluar Kost</h2>
                        <p class="text-muted">Kelola perpindahan kamar dan keluar kost penghuni</p>
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

                <div class="row">
                    <!-- Form Pindah Kamar -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-bed"></i> Pindah Kamar
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="id_kmr_penghuni" class="form-label">Penghuni Aktif</label>
                                        <select class="form-control" id="id_kmr_penghuni" name="id_kmr_penghuni" required>
                                            <option value="">Pilih Penghuni</option>
                                            <?php
                                            $query_penghuni_aktif = "SELECT kp.*, p.nama as nama_penghuni, p.no_ktp, k.nomor as nomor_kamar, k.harga
                                                                   FROM tb_kmr_penghuni kp 
                                                                   INNER JOIN tb_penghuni p ON kp.id_penghuni = p.id
                                                                   INNER JOIN tb_kamar k ON kp.id_kamar = k.id
                                                                   WHERE kp.tgl_keluar IS NULL
                                                                   ORDER BY p.nama";
                                            $result_penghuni_aktif = mysqli_query($conn, $query_penghuni_aktif);
                                            while ($penghuni = mysqli_fetch_assoc($result_penghuni_aktif)) {
                                                echo "<option value='" . $penghuni['id'] . "'>";
                                                echo htmlspecialchars($penghuni['nama_penghuni']) . " - " . $penghuni['nomor_kamar'];
                                                echo " (Rp " . number_format($penghuni['harga'], 0, ',', '.') . ")";
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="id_kamar_baru" class="form-label">Kamar Baru</label>
                                        <select class="form-control" id="id_kamar_baru" name="id_kamar_baru" required>
                                            <option value="">Pilih Kamar Baru</option>
                                            <?php
                                            $query_kamar_tersedia = "SELECT k.*, 
                                                                   CASE WHEN kp.id IS NULL THEN 'Tersedia' ELSE 'Terisi' END as status
                                                                   FROM tb_kamar k 
                                                                   LEFT JOIN tb_kmr_penghuni kp ON k.id = kp.id_kamar AND kp.tgl_keluar IS NULL
                                                                   ORDER BY k.nomor";
                                            $result_kamar_tersedia = mysqli_query($conn, $query_kamar_tersedia);
                                            while ($kamar = mysqli_fetch_assoc($result_kamar_tersedia)) {
                                                $disabled = ($kamar['status'] == 'Terisi') ? 'disabled' : '';
                                                echo "<option value='" . $kamar['id'] . "' $disabled>";
                                                echo $kamar['nomor'] . " - Rp " . number_format($kamar['harga'], 0, ',', '.') . " ($status)";
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tgl_pindah" class="form-label">Tanggal Pindah</label>
                                        <input type="date" class="form-control" id="tgl_pindah" name="tgl_pindah" 
                                               value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2" 
                                                  placeholder="Alasan pindah kamar"></textarea>
                                    </div>
                                    
                                    <button type="submit" name="pindah_kamar" class="btn btn-primary">
                                        <i class="fas fa-exchange-alt"></i> Proses Pindah Kamar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Form Keluar Kost -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-sign-out-alt"></i> Keluar Kost
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="id_penghuni" class="form-label">Penghuni Aktif</label>
                                        <select class="form-control" id="id_penghuni" name="id_penghuni" required>
                                            <option value="">Pilih Penghuni</option>
                                            <?php
                                            $query_penghuni_keluar = "SELECT p.*, k.nomor as nomor_kamar
                                                                     FROM tb_penghuni p 
                                                                     LEFT JOIN tb_kamar k ON p.kamar_id = k.id
                                                                     WHERE p.tgl_keluar IS NULL
                                                                     ORDER BY p.nama";
                                            $result_penghuni_keluar = mysqli_query($conn, $query_penghuni_keluar);
                                            while ($penghuni = mysqli_fetch_assoc($result_penghuni_keluar)) {
                                                echo "<option value='" . $penghuni['id'] . "'>";
                                                echo htmlspecialchars($penghuni['nama']) . " - " . $penghuni['no_ktp'];
                                                echo " (" . ($penghuni['nomor_kamar'] ? $penghuni['nomor_kamar'] : 'Tidak ada kamar') . ")";
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tgl_keluar" class="form-label">Tanggal Keluar</label>
                                        <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar" 
                                               value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="alasan" class="form-label">Alasan Keluar</label>
                                        <textarea class="form-control" id="alasan" name="alasan" rows="2" 
                                                  placeholder="Alasan keluar dari kost"></textarea>
                                    </div>
                                    
                                    <button type="submit" name="keluar_kost" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin memproses keluar kost? Data barang bawaan akan dihapus.')">
                                        <i class="fas fa-sign-out-alt"></i> Proses Keluar Kost
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pindah Kamar -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Pindah Kamar</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Penghuni</th>
                                        <th>Kamar Lama</th>
                                        <th>Kamar Baru</th>
                                        <th>Tanggal Pindah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_riwayat = "SELECT kp1.*, kp2.tgl_masuk as tgl_masuk_baru, 
                                                     p.nama as nama_penghuni,
                                                     k1.nomor as kamar_lama, k2.nomor as kamar_baru
                                                     FROM tb_kmr_penghuni kp1
                                                     INNER JOIN tb_kmr_penghuni kp2 ON kp1.id_penghuni = kp2.id_penghuni 
                                                     AND kp1.id != kp2.id AND kp1.tgl_keluar = kp2.tgl_masuk
                                                     INNER JOIN tb_penghuni p ON kp1.id_penghuni = p.id
                                                     INNER JOIN tb_kamar k1 ON kp1.id_kamar = k1.id
                                                     INNER JOIN tb_kamar k2 ON kp2.id_kamar = k2.id
                                                     WHERE kp1.tgl_keluar IS NOT NULL
                                                     ORDER BY kp1.tgl_keluar DESC";
                                    $result_riwayat = mysqli_query($conn, $query_riwayat);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result_riwayat)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['nama_penghuni']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($row['kamar_lama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['kamar_baru']) . "</td>";
                                        echo "<td>" . date('d/m/Y', strtotime($row['tgl_keluar'])) . "</td>";
                                        echo "<td><span class='badge bg-info'>Pindah Kamar</span></td>";
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