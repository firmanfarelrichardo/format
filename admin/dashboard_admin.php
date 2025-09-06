<?php
session_start();

// Keamanan: Cek apakah user sudah login dan role-nya adalah 'admin' atau 'superadmin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
    header("Location: ../public/login.html");
    exit();
}

// Sertakan header navigasi
include 'header.php';
?>
<title>Dashboard Admin - Portal Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        </div>

        <div class="admin-content">
            <div class="stats-summary-grid">
                <a href="manage_pengelola.php" class="summary-card">
                    <i class="fas fa-users-cog"></i>
                    <div class="summary-info">
                        <span class="number">Kelola Pengelola</span>
                        <span class="label">Manajemen akun pengelola</span>
                    </div>
                </a>
                <a href="manage_journal_status.php" class="summary-card">
                    <i class="fas fa-clipboard-check"></i>
                    <div class="summary-info">
                        <span class="number">Verifikasi Jurnal</span>
                        <span class="label">Review dan ubah status jurnal</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>