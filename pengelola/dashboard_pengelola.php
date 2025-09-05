<?php
session_start();
// Keamanan: Cek apakah user sudah login dan role-nya adalah 'pengelola'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
    header("Location: login.html");
    exit();
}
include 'header.php';
?>
<title>Dashboard Pengelola - Portal Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Dashboard Pengelola</h1>
            <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        </div>

        <div class="admin-content">
            <div class="stats-summary-grid">
                <a href="register_journal.php" class="summary-card">
                    <i class="fas fa-plus-square"></i>
                    <div class="summary-info">
                        <span class="number">Daftarkan Jurnal Baru</span>
                        <span class="label">Ajukan jurnal ke sistem</span>
                    </div>
                </a>
                <a href="view_my_submissions.php" class="summary-card">
                    <i class="fas fa-list-alt"></i>
                    <div class="summary-info">
                        <span class="number">Status Jurnal</span>
                        <span class="label">Lihat daftar jurnal yang diunggah</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>