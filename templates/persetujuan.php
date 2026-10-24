<?php $bodyClass = 'bg-pink'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <div class="layout-masuk">
        <div class="col-kiri">
            <h1 class="judul">Halo, <?= e($nama) ?>!</h1>
            <p>Sebelum mulai, ada hal yang perlu kamu tahu tentang datamu.</p>
        </div>
        <div class="col-kanan">
            <div class="kartu">
                <h2>Tentang datamu</h2>

                <?php require __DIR__ . '/partials/pesan-error.php'; ?>

                <!-- [MENUNGGU PEMILIK: keputusan no. 2, 3, 9, 10 PRD bagian 11].
                     Tiga bagian bertanda di bawah harus diisi sebelum rilis. Bahasa harus sederhana untuk remaja. -->
                <div class="persetujuan-konten">
                    <div class="persetujuan-bagian">
                        <h3>Apa yang disimpan?</h3>
                        <p>Namamu, pilihan jawabanmu di Sesi 1, ceritamu di Sesi 2, dan jawaban di Sesi 3 (kalau kamu mengisinya).</p>
                    </div>
                    <div class="persetujuan-bagian">
                        <h3>Siapa yang bisa membaca?</h3>
                        <p>Pemilik Website saja</p>
                    </div>
                    <div class="persetujuan-bagian">
                        <h3>Berapa lama disimpan?</h3>
                        <p>Setelah Seminggu di Hapus</p>
                    </div>
                    <div class="persetujuan-bagian">
                        <h3>Bagaimana cara menghapus?</h3>
                        <p>Meminta pengajuan terhadap pemilik website</p>
                    </div>
                </div>

                <form method="post">
                    <?= Security::getCsrfField() ?>
                    <button type="submit" class="tombol-primary">Saya mengerti dan setuju</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/foot.php'; ?>
