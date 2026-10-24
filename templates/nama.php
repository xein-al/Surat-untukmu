<?php $bodyClass = 'bg-pink'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <div class="layout-masuk">
        <div class="col-kiri">
            <h1 class="judul">Surat untukmu</h1>
            <div class="karakter">
                <img src="/assets/images/witch.svg" alt="" width="280" height="280">
            </div>
        </div>
        <div class="col-kanan">
            <div class="kartu">
                <h2>Siapa namamu?</h2>
                <p>Supaya suratnya terasa lebih personal untukmu.</p>

                <?php require __DIR__ . '/partials/pesan-error.php'; ?>

                <form method="post" novalidate>
                    <?= Security::getCsrfField() ?>
                    <label for="nama" class="label-input">Nama</label>
                    <input type="text" id="nama" name="nama" class="input-teks" maxlength="100"
                           value="<?= e($namaIsi) ?>" autocomplete="given-name" autofocus>
                    <button type="submit" class="tombol-primary">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/foot.php'; ?>
