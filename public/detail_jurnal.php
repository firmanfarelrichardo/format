<?php 
include '../layout/header.php';

// --- PENGAMBILAN PARAMETER ---
$journal_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$article_search = isset($_GET['q_article']) ? trim($_GET['q_article']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$results_per_page = 10;
$offset = ($page - 1) * $results_per_page;

if ($journal_id === 0) {
    echo "<main><div class='container my-5'><h1>Jurnal tidak ditemukan.</h1></div></main>";
    include 'footer.php'; exit();
}

// --- KONEKSI & AMBIL DATA JURNAL ---
$host = "localhost"; $user = "root"; $pass = ""; $db = "oai";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

$stmt_journal = $conn->prepare("SELECT * FROM jurnal_sumber WHERE id = ?");
$stmt_journal->bind_param("i", $journal_id);
$stmt_journal->execute();
$journal = $stmt_journal->get_result()->fetch_assoc();
$stmt_journal->close();

if (!$journal) {
    echo "<main><div class='container my-5'><h1>Jurnal tidak ditemukan.</h1></div></main>";
    include 'footer.php'; exit();
}

// --- LOGIKA PENCARIAN & PAGINATION ARTIKEL ---
$article_base_sql = "FROM artikel_oai WHERE journal_source_id = ?";
$param_types = "i";
$param_values = [$journal_id];

if (!empty($article_search)) {
    $article_base_sql .= " AND (title LIKE ? OR creator1 LIKE ?)";
    $param_types .= "ss";
    $search_term = "%" . $article_search . "%";
    array_push($param_values, $search_term, $search_term);
}

// Hitung total artikel
$count_sql = "SELECT COUNT(*) " . $article_base_sql;
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($param_types, ...$param_values);
$count_stmt->execute();
$total_articles = $count_stmt->get_result()->fetch_row()[0];
$total_pages = ceil($total_articles / $results_per_page);
$count_stmt->close();

// Ambil data artikel
$data_sql = "SELECT title, creator1, creator2, identifier1, description, source1 " . $article_base_sql . " ORDER BY id DESC LIMIT ? OFFSET ?";
$param_types .= "ii";
array_push($param_values, $results_per_page, $offset);
$data_stmt = $conn->prepare($data_sql);
$data_stmt->bind_param($param_types, ...$param_values);
$data_stmt->execute();
$articles_result = $data_stmt->get_result();
?>

<main class="page-container">
    <div class="container py-4">
        
        <!-- ====== KONTAINER 1: DETAIL JURNAL (TIDAK BERUBAH) ====== -->
        <div class="article-list1-container">
            <h1 class="journal-main-title"><?php echo htmlspecialchars($journal['journal_title']); ?></h1>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <img src="<?php echo htmlspecialchars(!empty($journal['cover_url']) ? $journal['cover_url'] : 'https://via.placeholder.com/150x210.png?text=Cover'); ?>" class="journal-cover-detail" alt="Cover">
                    </div>
                    <div class="col-md">
                        <p class="mb-1"><strong>Penersbit:</strong> <?php echo htmlspecialchars($journal['publisher_name'] ?? 'Universitas Lampung'); ?></p>
                        <p class="mb-1"><strong>ISSN:</strong> <?php echo htmlspecialchars($journal['issn'] ?? '-'); ?> | <strong>EISSN:</strong> <?php echo htmlspecialchars($journal['eissn'] ?? '-'); ?></p>
                        <p class="mb-1"><strong>DOI:</strong> <?php echo htmlspecialchars($journal['doi_prefix'] ?? '-'); ?></p>
                        <p class="mb-3"><strong>Website:</strong> <a href="<?php echo htmlspecialchars($journal['journal_website_url']); ?>" target="_blank">Kunjungi Situs <i class="fas fa-external-link-alt fa-xs"></i></a></p>
                        <div class="journal-tags">
                            <span class="tag"><?php echo htmlspecialchars($journal['fakultas']); ?></span> 
                            </div>
                    </div>
                </div>
            
                <p><strong><br> Tentang Jurnal:</strong></br> </p>
                <p class="journal-description"><?php echo nl2br(htmlspecialchars($journal['aim_and_scope'] ?? 'Tidak ada deskripsi.')); ?></p>
            </div>
        </div>

        <!-- ====== KONTAINER 2: DAFTAR ARTIKEL (MENIRU search_result.php) ====== -->
        <div class="article-list2-container">
            <div class="filter-bar-container mb-3">
                <h5 class="mb-0">Artikel (<?php echo $total_articles; ?>)</h5>
                <form action="detail_jurnal.php" method="GET" class="mini-search-form">
                    <input type="hidden" name="id" value="<?php echo $journal_id; ?>">
                    <div class="input-group">
                        <input type="search" name="q_article" class="form-control form-control-sm" placeholder="Cari dalam jurnal ini..." value="<?php echo htmlspecialchars($article_search); ?>">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <!-- Daftar Artikel (Struktur dari search_result.php) -->
            <div class="search-results-list">
                <?php if ($articles_result->num_rows > 0): ?>
                    <?php while ($article = $articles_result->fetch_assoc()): ?>
                        <div class="article-item">
                            <?php $link = !empty($article['identifier1']) ? $article['identifier1'] : '#'; ?>
                            <h4><a href="<?php echo htmlspecialchars($link); ?>" target="_blank"><?php echo htmlspecialchars($article['title']); ?></a></h4>
                            
                            <?php 
                                $creators = array_filter([$article['creator1'], $article['creator2']]);
                                echo '<p class="article-creator">Oleh: ' . htmlspecialchars(implode(', ', $creators)) . '</p>';
                            ?>

                            <?php 
                                $description_snippet = substr(strip_tags($article['description']), 0, 250);
                                echo '<p class="article-description">' . htmlspecialchars($description_snippet) . '...</p>';
                            ?>
                            
                            <p class="article-source">Sumber: <?php echo htmlspecialchars($article['source1']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada artikel yang ditemukan dengan kriteria Anda.</p>
                <?php endif; ?>
            </div>

            <!-- Navigasi Pagination (Struktur dari search_result.php) -->
            <nav class="pagination mt-4">
                <ul>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php $active_class = ($i == $page) ? 'active' : ''; ?>
                        <li>
                            <a href="?id=<?php echo $journal_id; ?>&q_article=<?php echo urlencode($article_search); ?>&page=<?php echo $i; ?>" class="<?php echo $active_class; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>

    </div>
</main>

<?php 
$data_stmt->close();
$conn->close();
include '../layout/footer.php'; 
?>