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
                <h2>Masukkan kode</h2>
                <p>Kamu perlu kode khusus untuk masuk. Tanyakan ke yang ngasih tau kamu tentang surat ini ya.</p>

                <?php require __DIR__ . '/partials/pesan-error.php'; ?>

                <form method="post" novalidate>
                    <?= Security::getCsrfField() ?>
                    <label for="code" class="label-input">Kode</label>
                    <input type="text" id="code" name="code" class="input-teks"
                           autocomplete="off" autocapitalize="off" spellcheck="false" autofocus>
                    <button type="submit" class="tombol-primary">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/foot.php'; ?>
