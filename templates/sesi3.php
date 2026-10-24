<?php $bodyClass = 'bg-mint'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <div class="layout-masuk">
        <div class="col-kiri">
            <h1 class="judul">Sedikit lebih dekat</h1>
            <p>Kamu boleh menjawab, atau melewatinya lewat tombol ✕.</p>
            <div class="karakter">
                <img src="/assets/images/witch.svg" alt="" width="280" height="280">
            </div>
        </div>
        <div class="col-kanan">
        <?php if ($selesai): ?>
            <div class="kartu kartu-selesai">
                <h2>Suratmu menunggu</h2>
                <a class="tombol-primary" href="/amplop.php">Lihat amplopnya</a>
            </div>
        <?php endif; ?>
        </div>
    </div>
</main>

<?php if (!$selesai): ?>
<!-- Pop up (AB-9: hanya tampilan; jawaban dikirim lewat form submit di akhir) -->
<div class="modal-overlay">
    <div class="modal-dialog-compact" role="dialog" aria-modal="true" aria-label="Pertanyaan tambahan">

        <!-- ✕ melewati seluruh sesi. Form terpisah agar tidak ikut mengirim isian. -->
        <form method="post" class="form-lewati">
            <?= Security::getCsrfField() ?>
            <button type="submit" name="lewati" value="1" class="tombol-tutup"
                    aria-label="Lewati seluruh sesi ini" title="Lewati sesi ini">&#10005;</button>
        </form>

        <form method="post" class="kartu-form" data-kartu-form data-wajib="0" novalidate>
            <?= Security::getCsrfField() ?>
            <?php require __DIR__ . '/partials/pesan-error.php'; ?>

            <div class="kartu-tumpukan">
            <?php foreach ($pertanyaan as $nomor => $teks): ?>
                <section class="pertanyaan-popup" data-kartu aria-labelledby="q<?= (int)$nomor ?>">
                    <p class="nomor-sesi">Sesi 3 · Pertanyaan <?= (int)$nomor ?> dari <?= count($pertanyaan) ?></p>
                    <h2 id="q<?= (int)$nomor ?>"><?= e($teks) ?></h2>
                    <textarea name="jawaban[<?= (int)$nomor ?>]" class="textarea-isian" rows="4"
                              aria-labelledby="q<?= (int)$nomor ?>"><?= e($isian[$nomor] ?? '') ?></textarea>
                    <p class="hint-teks">Boleh dikosongkan. Kalau mau melewati semuanya, tekan ✕.</p>
                </section>
            <?php endforeach; ?>
            </div>

            <p class="hint-teks" data-status role="status" aria-live="polite"></p>

            <div class="popup-aksi">
                <button type="button" class="tombol-secondary" data-prev hidden>Sebelumnya</button>
                <button type="button" class="tombol-primary" data-next hidden>Lanjut</button>
                <button type="submit" class="tombol-primary" data-submit>Buka suratku</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
<?php $scripts = ['kartu.js']; require __DIR__ . '/partials/foot.php'; ?>
