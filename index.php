<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/api.php';

$category = $_GET['category'] ?? 'technology';
$keyword = trim($_GET['q'] ?? '');

$allowedCategories = ['general', 'business', 'entertainment', 'health', 'science', 'sports', 'technology'];
if (!in_array($category, $allowedCategories, true)) {
    $category = 'technology';
}

$params = [
    'category' => $category,
    'pageSize' => 20
];
if ($keyword !== '') {
    $params['q'] = $keyword;
}

$result = getNews($params);
$articles = $result['data']['articles'] ?? [];
$error = $result['error'] ?? null;

function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
function displayDate($date): string {
    if (!$date) return '-';
    try {
        return (new DateTime($date))->format('d M Y, H:i');
    } catch (Exception $ex) {
        return '-';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News REST Client</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php">NEWS<span>CLIENT</span></a>
    <span class="tagline">PHP cURL · Public REST API</span>
</header>

<main class="container">
    <section class="hero">
        <p class="eyebrow">PUBLIC API PROJECT</p>
        <h1>Berita teknologi, <span>lebih mudah diakses.</span></h1>
        <p>Aplikasi REST Client sederhana yang mengambil data berita dari NewsAPI menggunakan PHP cURL.</p>
    </section>

    <section class="toolbar">
        <form method="GET" action="index.php" class="search-form">
            <input type="search" name="q" placeholder="Cari berita..." value="<?= e($keyword) ?>">
            <select name="category" aria-label="Kategori berita">
                <?php foreach ($allowedCategories as $item): ?>
                    <option value="<?= e($item) ?>" <?= $category === $item ? 'selected' : '' ?>>
                        <?= e(ucfirst($item)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Cari Berita</button>
        </form>
    </section>

    <?php if ($error): ?>
        <div class="notice error">
            <strong>Gagal mengambil data berita.</strong>
            <p><?= e($error) ?></p>
            <small>Periksa API key, koneksi internet, dan pengaturan cURL di XAMPP.</small>
        </div>
    <?php elseif (empty($articles)): ?>
        <div class="notice">Tidak ada berita ditemukan. Coba kata kunci atau kategori lain.</div>
    <?php else: ?>
        <div class="section-heading">
            <div>
                <p class="eyebrow">HASIL API</p>
                <h2><?= $keyword !== '' ? 'Hasil pencarian' : 'Berita ' . e(ucfirst($category)) ?></h2>
            </div>
            <span class="count"><?= count($articles) ?> artikel</span>
        </div>

        <section class="news-grid">
            <?php foreach ($articles as $article): ?>
                <article class="news-card">
                    <?php if (!empty($article['urlToImage'])): ?>
                        <img class="news-image" src="<?= e($article['urlToImage']) ?>" alt="Gambar berita"
                             onerror="this.style.display='none'">
                    <?php else: ?>
                        <div class="image-placeholder">NEWS</div>
                    <?php endif; ?>
                    <div class="news-content">
                        <div class="meta">
                            <span><?= e($article['source']['name'] ?? 'Sumber berita') ?></span>
                            <span><?= e(displayDate($article['publishedAt'] ?? null)) ?></span>
                        </div>
                        <h3><?= e($article['title'] ?? 'Tanpa judul') ?></h3>
                        <p><?= e($article['description'] ?? 'Tidak ada ringkasan berita.') ?></p>
                        <?php if (!empty($article['url'])): ?>
                            <a class="read-more" href="<?= e($article['url']) ?>" target="_blank" rel="noopener noreferrer">Baca selengkapnya ↗</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <footer>
        <p>Data berita disediakan oleh <a href="https://newsapi.org/" target="_blank" rel="noopener">NewsAPI.org</a>.</p>
        <p>Project praktikum System Testing · REST Client dengan PHP cURL</p>
    </footer>
</main>
</body>
</html>
