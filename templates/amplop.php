<?php $bodyClass = 'bg-biru'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <div class="amplop-container">
        <h1 class="judul">Suratmu sudah siap, <?= e($nama) ?></h1>
        <p class="penghitung-amplop" role="status"><?= (int)$jumlahBuka ?> dari 3 dibuka</p>

        <div class="karakter-amplop">
            <img src="/assets/images/spider-hero.svg" alt="" width="300" height="300">
        </div>

        <div class="amplop-grid">
            <!-- Amplop 1: dulu (merah muda) -->
            <a href="/surat.php?a=1" class="amplop amplop-pink<?= in_array(1, $amplopBuka, true) ? ' terbuka' : '' ?>">
                <span class="amplop-label">Surat 1</span>
                <span class="amplop-judul">Dulu</span>
                <?php if (in_array(1, $amplopBuka, true)): ?><span class="amplop-status">Sudah dibuka</span><?php endif; ?>
            </a>

            <!-- Amplop 2: sekarang (biru) -->
            <a href="/surat.php?a=2" class="amplop amplop-biru<?= in_array(2, $amplopBuka, true) ? ' terbuka' : '' ?>">
                <span class="amplop-label">Surat 2</span>
                <span class="amplop-judul">Sekarang</span>
                <?php if (in_array(2, $amplopBuka, true)): ?><span class="amplop-status">Sudah dibuka</span><?php endif; ?>
            </a>

            <!-- Amplop 3: nanti (lavender), terkunci sampai amplop 1 dan 2 dibuka (AB-2) -->
            <?php if ($amplop3Terkunci): ?>
                <button type="button" class="amplop amplop-lavender terkunci" aria-disabled="true">
                    <span class="amplop-label">Surat 3</span>
                    <span class="amplop-judul">Nanti</span>
                    <span class="amplop-status">Coming soon</span>
                </button>
            <?php else: ?>
                <a href="/surat.php?a=3" class="amplop amplop-lavender<?= in_array(3, $amplopBuka, true) ? ' terbuka' : '' ?>">
                    <span class="amplop-label">Surat 3</span>
                    <span class="amplop-judul">Nanti</span>
                    <?php if (in_array(3, $amplopBuka, true)): ?><span class="amplop-status">Sudah dibuka</span><?php endif; ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Tombol Keluar tidak ada di PRD. Menunggu keputusan pemilik: dipertahankan atau dihapus. -->
        <form method="post" action="/logout.php" class="tombol-keluar-container">
            <?= Security::getCsrfField() ?>
            <button type="submit" class="tombol-keluar">Keluar</button>
        </form>
    </div>
</main>
<?php require __DIR__ . '/partials/foot.php'; ?>
