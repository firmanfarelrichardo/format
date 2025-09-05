<?php
session_start();

// Keamanan: Cek apakah user sudah login dan role-nya adalah 'admin' atau 'superadmin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
    header("Location: login.html");
    exit();
}
include 'header.php';

// Koneksi ke Database
$host = "localhost";
$user = "root";
$pass = "";
$db = "oai";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = '';

// Logika untuk mengubah status jurnal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $journal_id = $_POST['journal_id'];
    $new_status = $_POST['new_status'];
    $oai_link = $_POST['oai_link'];

    $stmt = $conn->prepare("UPDATE jurnal_sumber SET status_approval = ?, oai_url = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_status, $oai_link, $journal_id);
    if ($stmt->execute()) {
        $message = "Status jurnal dan Link OAI berhasil diubah.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

?>
<title>Verifikasi Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Verifikasi Jurnal</h1>
            <p>Periksa formulir pendaftaran jurnal dan kelola statusnya.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <h3>Daftar Jurnal yang Diajukan</h3>
        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Judul Jurnal</th>
                        <th>Pengelola</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT id, journal_title, submitted_by_nip, status_approval FROM jurnal_sumber ORDER BY submitted_at DESC");
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['journal_title']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['submitted_by_nip']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['status_approval']) . '</td>';
                            echo '<td>';
                            echo '<a href="view_journal_details.php?id=' . $row['id'] . '" class="action-btn">Lihat & Edit</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">Tidak ada jurnal yang diajukan.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
$conn->close();
include 'footer.php';
?>