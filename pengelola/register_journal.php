<?php
session_start();
// FILE: register_journal.php
// Fungsi: Formulir pendaftaran jurnal lengkap untuk pengelola.
// Menggunakan skema database baru yang telah diperbarui.

// Keamanan: Cek apakah user sudah login dan role-nya adalah 'pengelola'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
    header("Location: login.html");
    exit();
}
include 'header.php';
?>
<title>Formulir Pendaftaran Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Formulir Pendaftaran Jurnal</h1>
            <p>Isi formulir di bawah ini untuk mendaftarkan jurnal kamu. Semua kolom wajib diisi.</p>
        </div>
        
        <div class="admin-form-container">
            <form action="review_submission.php" method="POST" class="admin-form">
                <input type="hidden" name="submitted_by_nip" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

                <!-- Fieldset: Informasi Kontak & Institusi -->
                <fieldset>
                    <legend>Informasi Kontak & Institusi</legend>
                    <div class="form-group">
                        <label for="nama_kontak">Nama Kontak PIC*</label>
                        <input type="text" id="nama_kontak" name="nama_kontak" required>
                    </div>
                    <div class="form-group">
                        <label for="email_kontak">Email Kontak PIC*</label>
                        <input type="email" id="email_kontak" name="email_kontak" required>
                    </div>
                    <div class="form-group">
                        <label for="journal_contact_phone">Nomor Telepon Kontak*</label>
                        <input type="text" id="journal_contact_phone" name="journal_contact_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="institusi">Institusi*</label>
                        <input type="text" id="institusi" name="institusi" required value="Universitas Lampung" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editorial_address">Alamat Editorial*</label>
                        <textarea id="editorial_address" name="editorial_address" rows="3" required></textarea>
                    </div>
                </fieldset>

                <!-- Fieldset: Detail Jurnal & Publikasi -->
                <fieldset>
                    <legend>Detail Jurnal & Publikasi</legend>
                    <div class="form-group">
                        <label for="judul_jurnal_asli">Judul Jurnal*</label>
                        <input type="text" id="judul_jurnal_asli" name="judul_jurnal_asli" required>
                    </div>
                     <div class="form-group">
                        <label for="journal_type">Tipe Jurnal*</label>
                        <select id="journal_type" name="journal_type" required>
                            <option value="Journal">Journal</option>
                            <option value="Conference">Conference</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="p_issn">P-ISSN*</label>
                        <input type="text" id="p_issn" name="p_issn" required>
                    </div>
                    <div class="form-group">
                        <label for="e_issn">E-ISSN*</label>
                        <input type="text" id="e_issn" name="e_issn" required>
                    </div>
                    <div class="form-group">
                        <label for="penerbit">Penerbit*</label>
                        <input type="text" id="penerbit" name="penerbit" required>
                    </div>
                    <div class="form-group">
                        <label for="country_of_publisher">Negara Penerbit*</label>
                        <input type="text" id="country_of_publisher" name="country_of_publisher" required value="Indonesia (ID)">
                    </div>
                     <div class="form-group">
                        <label for="fakultas">Fakultas*</label>
                        <select id="fakultas" name="fakultas" required>
                            <option value="">-- Pilih Fakultas --</option>
                            <option value="Fakultas Ekonomi dan Bisnis">Fakultas Ekonomi dan Bisnis</option>
                            <option value="Fakultas Hukum">Fakultas Hukum</option>
                            <option value="Fakultas Ilmu Sosial dan Ilmu Politik">Fakultas Ilmu Sosial dan Ilmu Politik</option>
                            <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
                            <option value="Fakultas Keguruan dan Ilmu Pendidikan">Fakultas Keguruan dan Ilmu Pendidikan</option>
                            <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam">Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                            <option value="Fakultas Pertanian">Fakultas Pertanian</option>
                            <option value="Fakultas Teknik">Fakultas Teknik</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_year">Tahun Mulai Terbit*</label>
                        <input type="number" id="start_year" name="start_year" min="1900" max="<?php echo date('Y'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="issue_period">Periode Terbit*</label>
                        <small class="form-text text-muted d-block mb-2">Pilih bulan-bulan terbit (contoh: Januari, Juni)</small>
                        <div class="row">
                            <?php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            foreach ($months as $month) {
                                echo '<div class="col-md-3 col-6">';
                                echo '<input type="checkbox" id="month_' . strtolower($month) . '" name="issue_period[]" value="' . $month . '"> ';
                                echo '<label for="month_' . strtolower($month) . '">' . $month . '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="aim_and_scope">Tujuan dan Ruang Lingkup (Aim & Scope)*</label>
                        <textarea id="aim_and_scope" name="aim_and_scope" rows="5" required></textarea>
                    </div>
                </fieldset>
                
                <!-- Fieldset: Tautan & Keterindeksan -->
                <fieldset>
                    <legend>Tautan & Keterindeksan</legend>
                    <div class="form-group">
                        <label for="website_url">URL Website Jurnal*</label>
                        <input type="url" id="website_url" name="website_url" placeholder="https://..." required>
                    </div>
                    <div class="form-group">
                        <label for="link_oai">Link OAI-PMH*</label>
                        <input type="url" id="link_oai" name="link_oai" placeholder="https://.../oai" required>
                    </div>
                    <div class="form-group">
                        <label for="url_cover">URL Cover Jurnal*</label>
                        <input type="url" id="url_cover" name="url_cover" placeholder="https://..." required>
                    </div>
                    <div class="form-group">
                        <label for="url_editorial_board">URL Dewan Editorial*</label>
                        <input type="url" id="url_editorial_board" name="url_editorial_board" required>
                    </div>
                    <div class="form-group">
                        <label for="url_google_scholar">URL Google Scholar</label>
                        <input type="url" id="url_google_scholar" name="url_google_scholar" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label for="link_sinta">Link Sinta</label>
                        <input type="url" id="link_sinta" name="link_sinta" placeholder="https://sinta.kemdikbud.go.id/...">
                    </div>
                    <div class="form-group">
                        <label for="link_garuda">Link Garuda</label>
                        <input type="url" id="link_garuda" name="link_garuda" placeholder="https://garuda.ristekbrin.go.id/...">
                    </div>
                </fieldset>

                <!-- Fieldset: Kategori dan Indeks -->
                <fieldset>
                    <legend>Kategori & Akreditasi</legend>
                    <div class="form-group">
                        <label for="akreditasi_sinta">Akreditasi SINTA*</label>
                        <select id="akreditasi_sinta" name="akreditasi_sinta" required>
                            <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                            <option value="Sinta 1">Sinta 1</option>
                            <option value="Sinta 2">Sinta 2</option>
                            <option value="Sinta 3">Sinta 3</option>
                            <option value="Sinta 4">Sinta 4</option>
                            <option value="Sinta 5">Sinta 5</option>
                            <option value="Sinta 6">Sinta 6</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="index_scopus">Indeks Scopus</label>
                        <select id="index_scopus" name="index_scopus">
                            <option value="Belum Terindeks">Belum Terindeks</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="subject_garuda">Subjek Garuda (pisahkan dengan koma)*</label>
                        <textarea id="subject_garuda" name="subject_garuda" rows="3" required></textarea>
                    </div>
                </fieldset>

                <button type="submit" class="submit-btn">Review & Ajukan Jurnal</button>
            </form>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>