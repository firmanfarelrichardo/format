<?php
session_start();
// FILE: review_submission.php
// Fungsi: Menampilkan data formulir untuk ditinjau oleh pengelola sebelum finalisasi.

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
    header("Location: login.html");
    exit();
}
include 'header.php';

$form_data = $_POST;

// Validasi sederhana di sisi server untuk memastikan data terkirim
if (empty($form_data)) {
    die("<main class='page-container'><div class='container'><p>Data formulir tidak ditemukan. Silakan kembali dan isi ulang.</p><a href='register_journal.php'>Kembali</a></div></main>");
}
?>
<title>Review Pengajuan Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Review Pengajuan Jurnal</h1>
            <p>Mohon periksa kembali detail jurnal di bawah ini. Ini adalah langkah verifikasi terakhir.</p>
        </div>
        
        <div class="review-content">
            <div class="review-details">
                <h3>Detail Jurnal yang Diajukan</h3>
                <p><strong>Judul Jurnal:</strong> <?php echo htmlspecialchars($form_data['judul_jurnal_asli']); ?></p>
                <p><strong>Tipe Jurnal:</strong> <?php echo htmlspecialchars($form_data['journal_type']); ?></p>
                <p><strong>Fakultas:</strong> <?php echo htmlspecialchars($form_data['fakultas']); ?></p>
                <p><strong>URL Website:</strong> <a href="<?php echo htmlspecialchars($form_data['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['website_url']); ?></a></p>
                <p><strong>Nama Kontak:</strong> <?php echo htmlspecialchars($form_data['nama_kontak']); ?></p>
                <p><strong>Email Kontak:</strong> <?php echo htmlspecialchars($form_data['email_kontak']); ?></p>
                <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($form_data['journal_contact_phone']); ?></p>
                <p><strong>Institusi:</strong> <?php echo htmlspecialchars($form_data['institusi']); ?></p>
                <p><strong>Alamat Editorial:</strong> <?php echo nl2br(htmlspecialchars($form_data['editorial_address'])); ?></p>
                <p><strong>P-ISSN:</strong> <?php echo htmlspecialchars($form_data['p_issn']); ?></p>
                <p><strong>E-ISSN:</strong> <?php echo htmlspecialchars($form_data['e_issn']); ?></p>
                <p><strong>Penerbit:</strong> <?php echo htmlspecialchars($form_data['penerbit']); ?></p>
                <p><strong>Negara Penerbit:</strong> <?php echo htmlspecialchars($form_data['country_of_publisher']); ?></p>
                <p><strong>Tahun Mulai Terbit:</strong> <?php echo htmlspecialchars($form_data['start_year']); ?></p>
                <p><strong>Periode Terbit:</strong> <?php echo htmlspecialchars(implode(', ', $_POST['issue_period'])); ?></p>
                <p><strong>Tujuan dan Ruang Lingkup:</strong> <?php echo nl2br(htmlspecialchars($form_data['aim_and_scope'])); ?></p>
                <p><strong>Link OAI-PMH:</strong> <a href="<?php echo htmlspecialchars($form_data['link_oai']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['link_oai']); ?></a></p>
                <p><strong>URL Cover:</strong> <a href="<?php echo htmlspecialchars($form_data['url_cover']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['url_cover']); ?></a></p>
                <p><strong>URL Dewan Editorial:</strong> <a href="<?php echo htmlspecialchars($form_data['url_editorial_board']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['url_editorial_board']); ?></a></p>
                <p><strong>URL Google Scholar:</strong> <a href="<?php echo htmlspecialchars($form_data['url_google_scholar']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['url_google_scholar']); ?></a></p>
                <p><strong>Link SINTA:</strong> <a href="<?php echo htmlspecialchars($form_data['link_sinta']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['link_sinta']); ?></a></p>
                <p><strong>Link Garuda:</strong> <a href="<?php echo htmlspecialchars($form_data['link_garuda']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['link_garuda']); ?></a></p>
                <p><strong>Akreditasi SINTA:</strong> <?php echo htmlspecialchars($form_data['akreditasi_sinta']); ?></p>
                <p><strong>Indeks Scopus:</strong> <?php echo htmlspecialchars($form_data['index_scopus']); ?></p>
                <p><strong>Subjek Garuda:</strong> <?php echo htmlspecialchars($form_data['subject_garuda']); ?></p>
            </div>
            
            <hr style="margin: 40px 0;">

            <!-- Form Finalisasi Verifikasi -->
            <div class="admin-form-container">
                <h3>Verifikasi & Unggah</h3>
                <p>Klik tombol di bawah ini untuk mengunggah formulir. Setelah diunggah, data akan masuk ke daftar verifikasi Admin.</p>
                <form action="api/submit_submission.php" method="POST">
                    <?php
                    // Mengubah semua data form menjadi input hidden
                    foreach ($form_data as $key => $value) {
                         if ($key === 'issue_period' && is_array($value)) {
                            // Khusus untuk array, gabungkan dengan koma
                            $value = implode(',', $value);
                         }
                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                    }
                    ?>
                    <button type="submit" class="submit-btn" style="width:100%;">Unggah Jurnal</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>