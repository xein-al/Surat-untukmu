<?php $bodyClass = 'bg-pink'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container halaman-pesan">
    <h1 class="judul"><?= e($judulHalaman) ?></h1>
    <p><?= e($pesan) ?></p>
    <a href="/index.php" class="tombol-secondary">Kembali ke awal</a>
</main>
<?php require __DIR__ . '/partials/foot.php'; ?>
