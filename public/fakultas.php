<?php include '../layout/header.php'; ?>

<main>
    <div class="page-title-container">
        <div class="container">
            <h1>Telusuri Berdasarkan Fakultas</h1>
            <p>Pilih fakultas untuk melihat semua artikel yang berafiliasi.</p>
        </div>
    </div>

    <div class="container page-content"><br>
        <div class="fakultas-grid">
            <?php
            $fakultas_list = [
                'Teknik' => 'fas fa-cogs',
                'Pertanian' => 'fas fa-seedling',
                'Kedokteran' => 'fas fa-stethoscope',
                'Hukum' => 'fas fa-gavel',
                'Ilmu Sosial dan Politik' => 'fas fa-users',
                'MIPA' => 'fas fa-flask',
                'Keguruan dan Ilmu Pendidikan' => 'fas fa-chalkboard-teacher',
                'Ekonomi dan Bisnis' => 'fas fa-chart-line'
            ];

            foreach ($fakultas_list as $nama => $icon) {
                echo '<a href="jurnal_fak.php?fakultas=' . urlencode($nama) . '" class="fakultas-card">';
                echo '<div class="fakultas-icon"><i class="' . $icon . '"></i></div>';
                echo '<h3>' . htmlspecialchars($nama) . '</h3>';
                // Anda bisa menambahkan query untuk menghitung jumlah artikel/jurnal per fakultas di sini jika perlu
                echo '<span class="fakultas-link">Lihat Artikel <i class="fas fa-arrow-right"></i></span>';
                echo '</a>';
            }
            ?>
        </div>
        <br>
    </div>
</main>

<?php include '../layout/footer.php'; ?>