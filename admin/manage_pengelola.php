<?php
// FILE: manage_pengelola.php
// Fungsi: Halaman admin untuk mengelola akun pengelola (tambah, edit, hapus).
// Tujuan: Memungkinkan superadmin atau admin mengelola pengguna dengan role 'pengelola'.

session_start();

// Mengamankan halaman: hanya bisa diakses oleh admin dan superadmin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'superadmin' && $_SESSION['user_role'] !== 'admin')) {
    header("Location: ../login.html");
    exit();
}

// Sertakan file koneksi database dan header
include '../layout/header.php';
require_once '../database/db.php';

$message = '';
$conn = connect_to_database();

// --- Logika CRUD untuk Pengelola ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menambahkan pengelola baru
    if (isset($_POST['add'])) {
        $nip = $_POST['nip'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = 'pengelola';

        $stmt = $conn->prepare("INSERT INTO users (nip, nama, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nip, $nama, $email, $password, $role);
        if ($stmt->execute()) {
            $message = "Pengelola berhasil ditambahkan.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    // Mengubah data pengelola
    elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $nip = $_POST['nip'];
        
        $stmt = $conn->prepare("UPDATE users SET nama = ?, email = ?, nip = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nama, $email, $nip, $id);
        if ($stmt->execute()) {
            $message = "Pengelola berhasil diubah.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    // Menghapus pengelola
    elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Pengelola berhasil dihapus.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Mengambil data pengelola untuk ditampilkan
$pengelola_list = [];
$result = $conn->query("SELECT id, nip, nama, email FROM users WHERE role = 'pengelola'");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pengelola_list[] = $row;
    }
}
$conn->close();
?>

<title>Manajemen Pengelola - Portal Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Manajemen Akun Pengelola</h1>
            <p>Tambah, edit, atau hapus akun pengelola.</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="admin-form-container">
            <h3>Tambah Pengelola Baru</h3>
            <form action="manage_pengelola.php" method="POST" class="admin-form">
                <input type="hidden" name="add" value="1">
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" id="nip" name="nip" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">Tambah Pengelola</button>
            </form>
        </div>
        
        <hr style="margin: 40px 0;">

        <h3>Daftar Pengelola</h3>
        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pengelola_list)): ?>
                        <?php foreach ($pengelola_list as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nip']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <a href="#" class="action-btn" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nip']); ?>', '<?php echo htmlspecialchars($row['nama']); ?>', '<?php echo htmlspecialchars($row['email']); ?>')">Edit</a>
                                    <a href="#" class="action-btn-secondary" onclick="document.getElementById('delete-form-<?php echo $row['id']; ?>').submit();">Hapus</a>
                                    <form id="delete-form-<?php echo $row['id']; ?>" action="manage_pengelola.php" method="POST" style="display:none;">
                                        <input type="hidden" name="delete" value="1">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Tidak ada akun pengelola.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div id="editModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h3>Edit Pengelola</h3>
                <form action="manage_pengelola.php" method="POST">
                    <input type="hidden" name="edit" value="1">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label for="edit-nip">NIP</label>
                        <input type="text" id="edit-nip" name="nip" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-nama">Nama</label>
                        <input type="text" id="edit-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email</label>
                        <input type="email" id="edit-email" name="email" required>
                    </div>
                    <button type="submit" class="submit-btn">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function openEditModal(id, nip, nama, email) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nip').value = nip;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-email').value = email;
        document.getElementById('editModal').style.display = 'block';
    }

    document.querySelector('.close-btn').onclick = function() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
            document.getElementById('editModal').style.display = 'none';
        }
    }
</script>
<?php include '../layout/footer.php'; ?>