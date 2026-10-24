<?php $bodyClass = 'bg-pink'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <div class="layout-masuk">
        <div class="col-kiri">
            <h1 class="judul">Kenali rasamu</h1>
            <p>Tidak ada jawaban benar atau salah. Pilih yang paling dekat dengan kondisimu sekarang.</p>
            <div class="karakter">
                <img src="/assets/images/witch.svg" alt="" width="280" height="280">
            </div>
        </div>
        <div class="col-kanan">
        <?php if ($selesai): ?>
            <div class="kartu kartu-selesai">
                <h2>Terima kasih sudah menjawab, <?= e($nama) ?>.</h2>
                <a class="tombol-primary" href="/sesi2.php">Lanjut ke Sesi 2</a>
            </div>
        <?php else: ?>
            <form method="post" class="kartu-form" data-kartu-form data-wajib="1" novalidate>
                <?= Security::getCsrfField() ?>
                <?php require __DIR__ . '/partials/pesan-error.php'; ?>

                <div class="kartu-area">
                    <button type="button" class="panah" data-prev aria-label="Pertanyaan sebelumnya" hidden>&#8249;</button>

                    <div class="kartu-tumpukan">
                    <?php foreach ($pertanyaan as $nomor => $p): ?>
                        <section class="kartu pertanyaan-kartu" data-kartu aria-labelledby="q<?= (int)$nomor ?>">
                            <p class="nomor-sesi">Sesi 1 · Pertanyaan <?= (int)$nomor ?> dari <?= count($pertanyaan) ?></p>
                            <h2 id="q<?= (int)$nomor ?>"><?= e($p['teks']) ?></h2>
                            <div class="opsi-grup" role="radiogroup" aria-labelledby="q<?= (int)$nomor ?>">
                            <?php foreach ($p['opsi'] as $huruf => $label): ?>
                                <label class="opsi-pilihan">
                                    <input type="radio" role="radio" name="jawaban[<?= (int)$nomor ?>]"
                                           value="<?= e($huruf) ?>"<?= (($terpilih[$nomor] ?? '') === $huruf) ? ' checked' : '' ?>>
                                    <span class="opsi-label">
                                        <span class="opsi-huruf" aria-hidden="true"><?= e($huruf) ?></span>
                                        <span class="opsi-teks"><?= e($label) ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                    </div>

                    <button type="button" class="panah" data-next aria-label="Pertanyaan berikutnya" hidden>&#8250;</button>
                </div>

                <p class="hint-teks" data-status role="status" aria-live="polite"></p>

                <div class="kartu-aksi">
                    <button type="submit" class="tombol-primary" data-submit>Lanjut ke Sesi 2</button>
                </div>
            </form>
        <?php endif; ?>
        </div>
    </div>
</main>
<?php $scripts = ['kartu.js']; require __DIR__ . '/partials/foot.php'; ?>
