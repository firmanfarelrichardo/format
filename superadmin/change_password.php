<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
    header("Location: login.html");
    exit();
}
include 'header.php';
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Koneksi ke Database
    $host = "localhost"; $user = "root"; $pass = ""; $db = "oai";
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }
    
    $stmt = $conn->prepare("SELECT password FROM users WHERE nip = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE nip = ?");
            $update_stmt->bind_param("ss", $hashed_password, $_SESSION['user_id']);
            if ($update_stmt->execute()) {
                $message = "Password berhasil diubah!";
            } else {
                $message = "Error saat mengubah password: " . $update_stmt->error;
            }
            $update_stmt->close();
        } else {
            $message = "Password baru dan konfirmasi tidak cocok.";
        }
    } else {
        $message = "Password lama salah.";
    }
    $conn->close();
}
?>
<title>Ganti Password</title>
<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Ganti Password</h1>
            <p>Ubah password akun superadmin kamu.</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="admin-form-container" style="max-width: 500px; margin: 0 auto;">
            <form action="change_password.php" method="POST" class="admin-form">
                <div class="form-group">
                    <label for="current_password">Password Lama</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="submit-btn">Ubah Password</button>
            </form>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>