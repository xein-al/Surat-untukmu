<?php $bodyClass = 'bg-lavender'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <div class="layout-masuk">
        <div class="col-kiri">
            <h1 class="judul">Cerita hari ini</h1>
            <p>Tulis apa yang terlintas di pikiranmu. Kalau ada yang nggak pengen dijawab, boleh dikosongkan.</p>
            <div class="karakter">
                <img src="/assets/images/witch-pet.svg" alt="" width="280" height="280">
            </div>
        </div>
        <div class="col-kanan">
        <?php if ($selesai): ?>
            <div class="kartu kartu-selesai">
                <h2>Terima kasih sudah bercerita, <?= e($nama) ?>.</h2>
                <a class="tombol-primary" href="/sesi3.php">Lanjut ke Sesi 3</a>
            </div>
        <?php else: ?>
            <form method="post" class="kartu-form" data-kartu-form data-wajib="0" novalidate>
                <?= Security::getCsrfField() ?>
                <?php require __DIR__ . '/partials/pesan-error.php'; ?>

                <div class="kartu-area">
                    <button type="button" class="panah" data-prev aria-label="Pertanyaan sebelumnya" hidden>&#8249;</button>

                    <div class="kartu-tumpukan">
                    <?php foreach ($pertanyaan as $nomor => $teks): ?>
                        <section class="kartu pertanyaan-kartu" data-kartu aria-labelledby="q<?= (int)$nomor ?>">
                            <p class="nomor-sesi">Sesi 2 · Pertanyaan <?= (int)$nomor ?> dari <?= count($pertanyaan) ?></p>
                            <h2 id="q<?= (int)$nomor ?>"><?= e($teks) ?></h2>
                            <textarea name="jawaban[<?= (int)$nomor ?>]" class="textarea-isian" rows="5"
                                      aria-labelledby="q<?= (int)$nomor ?>"><?= e($isian[$nomor] ?? '') ?></textarea>
                            <p class="hint-teks">Boleh dikosongkan.</p>
                        </section>
                    <?php endforeach; ?>
                    </div>

                    <button type="button" class="panah" data-next aria-label="Pertanyaan berikutnya" hidden>&#8250;</button>
                </div>

                <p class="hint-teks" data-status role="status" aria-live="polite"></p>

                <div class="kartu-aksi">
                    <button type="submit" class="tombol-primary" data-submit>Lanjut ke Sesi 3</button>
                </div>
            </form>
        <?php endif; ?>
        </div>
    </div>
</main>
<?php $scripts = ['kartu.js']; require __DIR__ . '/partials/foot.php'; ?>
