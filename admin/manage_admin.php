<?php
session_start();
include '../database/config.php';
include 'auth_check.php';

// Cek apakah user adalah super admin
if ($_SESSION['admin_level'] !== 'super_admin') {
    $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini!";
    header("Location: index.php");
    exit();
}

// Proses tambah admin
if (isset($_POST['tambah'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $level = $_POST['level'];
    
    // Cek username sudah ada atau belum
    $check_query = "SELECT * FROM tb_admin WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "Username sudah digunakan!";
        header("Location: manage_admin.php");
        exit();
    }
    
    $query = "INSERT INTO tb_admin (username, password, nama, email, level) 
              VALUES ('$username', '$password', '$nama', '$email', '$level')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data admin berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: manage_admin.php");
    exit();
}

// Proses update admin
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $level = $_POST['level'];
    $status = $_POST['status'];
    
    $query = "UPDATE tb_admin SET nama='$nama', email='$email', level='$level', status='$status' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data admin berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: manage_admin.php");
    exit();
}

// Proses reset password
if (isset($_POST['reset_password'])) {
    $id = $_POST['id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    $query = "UPDATE tb_admin SET password='$new_password' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Password admin berhasil direset!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: manage_admin.php");
    exit();
}

// Proses hapus admin
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Jangan hapus diri sendiri
    if ($id == $_SESSION['admin_id']) {
        $_SESSION['error'] = "Anda tidak dapat menghapus akun sendiri!";
        header("Location: manage_admin.php");
        exit();
    }
    
    $query = "DELETE FROM tb_admin WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data admin berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: manage_admin.php");
    exit();
}

// Ambil data admin untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM tb_admin WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - Admin Panel Kost Manager</title>
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
        .status-aktif {
            background-color: #28a745;
            color: white;
        }
        .status-nonaktif {
            background-color: #dc3545;
            color: white;
        }
        .level-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .level-super {
            background-color: #ffc107;
            color: black;
        }
        .level-admin {
            background-color: #17a2b8;
            color: white;
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
                        <a class="nav-link" href="pembayaran.php">
                            <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
                        </a>
                        <a class="nav-link" href="pindah_kamar.php">
                            <i class="fas fa-exchange-alt me-2"></i> Pindah Kamar
                        </a>
                        <a class="nav-link" href="laporan.php">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a>
                        <a class="nav-link active" href="manage_admin.php">
                            <i class="fas fa-users-cog me-2"></i> Kelola Admin
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
                        <h2 class="mb-4"><i class="fas fa-users-cog"></i> Kelola Admin</h2>
                        <p class="text-muted">Kelola data administrator sistem</p>
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

                <!-- Form Tambah/Edit Admin -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> <?php echo $edit_data ? 'Edit Admin' : 'Tambah Admin Baru'; ?>
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
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?php echo $edit_data ? htmlspecialchars($edit_data['username']) : ''; ?>"
                                               <?php echo $edit_data ? 'readonly' : 'required'; ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama']) : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo $edit_data ? htmlspecialchars($edit_data['email']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Level</label>
                                        <select class="form-control" id="level" name="level" required>
                                            <option value="">Pilih Level</option>
                                            <option value="admin" <?php echo ($edit_data && $edit_data['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                            <option value="super_admin" <?php echo ($edit_data && $edit_data['level'] == 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!$edit_data): ?>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="aktif" <?php echo ($edit_data['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="nonaktif" <?php echo ($edit_data['status'] == 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="<?php echo $edit_data ? 'update' : 'tambah'; ?>" class="btn btn-primary">
                                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if ($edit_data): ?>
                                    <a href="manage_admin.php" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Admin -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Admin</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tb_admin ORDER BY created_at DESC";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $level_class = $row['level'] == 'super_admin' ? 'level-super' : 'level-admin';
                                        $status_class = $row['status'] == 'aktif' ? 'status-aktif' : 'status-nonaktif';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['username']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td><span class='level-badge $level_class'>" . ucfirst(str_replace('_', ' ', $row['level'])) . "</span></td>";
                                        echo "<td><span class='status-badge $status_class'>" . ucfirst($row['status']) . "</span></td>";
                                        echo "<td>" . ($row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : '-') . "</td>";
                                        echo "<td>";
                                        echo "<a href='manage_admin.php?edit=" . $row['id'] . "' class='btn btn-sm btn-warning me-1'><i class='fas fa-edit'></i></a>";
                                        
                                        // Tombol reset password
                                        echo "<button type='button' class='btn btn-sm btn-info me-1' data-bs-toggle='modal' data-bs-target='#resetModal' data-id='" . $row['id'] . "' data-username='" . htmlspecialchars($row['username']) . "'><i class='fas fa-key'></i></button>";
                                        
                                        // Jangan tampilkan tombol hapus untuk diri sendiri
                                        if ($row['id'] != $_SESSION['admin_id']) {
                                            echo "<a href='manage_admin.php?hapus=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus admin ini?\")'><i class='fas fa-trash'></i></a>";
                                        }
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

    <!-- Modal Reset Password -->
    <div class="modal fade" id="resetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="reset_admin_id">
                        <p>Reset password untuk admin: <strong id="reset_username"></strong></p>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk modal reset password
        document.addEventListener('DOMContentLoaded', function() {
            var resetModal = document.getElementById('resetModal');
            resetModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var adminId = button.getAttribute('data-id');
                var username = button.getAttribute('data-username');
                
                document.getElementById('reset_admin_id').value = adminId;
                document.getElementById('reset_username').textContent = username;
            });
        });
    </script>
</body>
</html> 