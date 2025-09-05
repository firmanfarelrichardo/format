<?php
session_start();
// FILE: view_my_submissions.php
// Fungsi: Menampilkan daftar jurnal yang diajukan oleh pengelola yang sedang login.
// Juga menyediakan opsi untuk mengajukan edit atau hapus.

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
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

$pengelola_nip = $_SESSION['user_id'];

// Ambil ID pengelola dari NIP
$stmt_id = $conn->prepare("SELECT id FROM users WHERE nip = ?");
$stmt_id->bind_param("s", $pengelola_nip);
$stmt_id->execute();
$pengelola_id = $stmt_id->get_result()->fetch_assoc()['id'];
$stmt_id->close();

?>
<title>Pengajuan Jurnal Saya</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Jurnal yang Diajukan</h1>
            <p>Berikut adalah daftar jurnal yang telah kamu ajukan. Kamu bisa melihat statusnya di sini.</p>
        </div>

        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Judul Jurnal</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt_journals = $conn->prepare("SELECT id, judul_jurnal_asli, status, submitted_at FROM jurnal_sumber WHERE pengelola_id = ? ORDER BY submitted_at DESC");
                    $stmt_journals->bind_param("i", $pengelola_id);
                    $stmt_journals->execute();
                    $result = $stmt_journals->get_result();

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['judul_jurnal_asli']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['submitted_at']) . '</td>';
                            echo '<td>';
                            echo '<button class="action-btn" onclick="requestEdit('.$row['id'].')">Ajukan Edit</button> ';
                            echo '<button class="action-btn-secondary" onclick="requestDelete('.$row['id'].')">Ajukan Hapus</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">Tidak ada jurnal yang kamu ajukan.</td></tr>';
                    }
                    $stmt_journals->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    function requestEdit(id) {
        alert('Permintaan edit untuk jurnal ID ' + id + ' telah diajukan ke admin.');
        // Di masa depan, logika ini bisa mengirim permintaan ke API
        // yang akan memperbarui status jurnal di database
    }

    function requestDelete(id) {
        if (confirm('Apakah kamu yakin ingin mengajukan penghapusan jurnal ini?')) {
            alert('Permintaan hapus untuk jurnal ID ' + id + ' telah diajukan ke admin.');
            // Di masa depan, logika ini bisa mengirim permintaan ke API
        }
    }
</script>
<?php
$conn->close();
include 'footer.php';
?>