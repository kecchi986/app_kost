<?php
session_start();

// Hapus semua session admin
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_nama']);
unset($_SESSION['admin_level']);

// Destroy session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?> 