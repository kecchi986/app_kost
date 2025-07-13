<?php
// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Cek apakah session admin masih valid
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?> 