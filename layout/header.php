<?php
// FILE: includes/header.php
// Fungsi: Menyediakan header dan navigasi yang konsisten untuk semua halaman.

// Memulai session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mendapatkan path root relatif secara dinamis
$current_path = $_SERVER['PHP_SELF'];
$path_parts = explode('/', $current_path);
// Cek apakah file berada di dalam salah satu folder role (superadmin, admin, pengelola)
$is_role_page = in_array('superadmin', $path_parts) || in_array('admin', $path_parts) || in_array('pengelola', $path_parts);

// Jika di dalam folder role, path relatif kembali satu tingkat
$rel_path = $is_role_page ? '../' : '';

// Mengarahkan ke halaman login jika tidak ada session aktif dan bukan halaman login itu sendiri
$current_file = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user_id']) && $current_file !== 'login.html') {
    header("Location: " . $rel_path . "public/login.html");
    exit();
}

// Menggunakan path yang dinamis
include_once '../database/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Jurnal</title>
    <link rel="stylesheet" href="<?php echo $rel_path; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $rel_path; ?>assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-left">
                <a href="<?php echo $rel_path; ?>public/index.php" class="logo">
                    <img src="<?php echo $rel_path; ?>assets/images/Unila E-Journal System.png" alt="Logo Universitas Lampung">
                </a>
                <nav class="main-nav">
                    <ul>
                        <li class="<?php if ($current_page == 'index.php') { echo 'active'; } ?>">
                            <a href="<?php echo $rel_path; ?>public/index.php">Home</a>
                        </li>
                        <li class="<?php if ($current_page == 'fakultas.php' || $current_page == 'jurnal_fak.php') { echo 'active'; } ?>">
                            <a href="<?php echo $rel_path; ?>public/fakultas.php">Fakultas</a>
                        </li>
                        <li class="<?php if ($current_page == 'penerbit.php') { echo 'active'; } ?>">
                            <a href="<?php echo $rel_path; ?>public/penerbit.php">Penerbit</a>
                        </li>
                        <li class="<?php if ($current_page == 'subjek.php') { echo 'active'; } ?>">
                            <a href="<?php echo $rel_path; ?>public/subjek.php">Subjek</a>
                        </li>
                        <li class="<?php if ($current_page == 'statistik.php') { echo 'active'; } ?>">
                            <a href="<?php echo $rel_path; ?>public/statistik.php">Statistik</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="header-right">
                <div class="user-actions">
                    <?php if (isset($_SESSION['user_id'])):
                        $role_dir = '';
                        if ($_SESSION['user_role'] === 'superadmin') {
                            $role_dir = 'superadmin';
                        } else if ($_SESSION['user_role'] === 'admin') {
                            $role_dir = 'admin';
                        } else if ($_SESSION['user_role'] === 'pengelola') {
                            $role_dir = 'pengelola';
                        }
                    ?>
                        <a href="<?php echo $rel_path . $role_dir; ?>/dashboard_superadmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a href="<?php echo $rel_path; ?>api/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <?php else: ?>
                        <a href="<?php echo $rel_path; ?>public/login.html"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>