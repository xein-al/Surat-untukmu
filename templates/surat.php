<?php $bodyClass = 'bg-biru'; require __DIR__ . '/partials/head.php'; ?>
<main id="isi" class="container">
    <!-- Modal surat (F-11). Esc menutup, ditangani di surat.js. -->
    <div class="modal-overlay">
        <div class="modal-surat" role="dialog" aria-modal="true" aria-labelledby="judul-surat">
            <a href="/amplop.php" class="tombol-tutup-surat" data-tutup aria-label="Tutup surat">&#10005;</a>

            <div class="surat-konten">
                <div class="surat-kiri">
                    <h1 class="surat-judul" id="judul-surat"><?= e($judulSurat[$amplopId]) ?></h1>

                    <div class="surat-teks tulisan-tangan"><?= nl2br(e($isiSurat[$amplopId])) ?></div>

                    <?php if ($tampilKotakAnjuran): ?>
                        <!-- Kotak anjuran (F-12). AB-6: teks tidak boleh diperlemah, dipotong, atau disembunyikan. -->
                        <div class="kotak-anjuran" role="note">
                            <!-- [MENUNGGU PEMILIK: teks lengkap kotak anjuran (keputusan no. 6)
                                 dan kontak bantuan resmi (keputusan no. 5). Jangan mengarang nomor atau layanan.] -->
                            <p>[MENUNGGU PEMILIK: teks kotak anjuran]</p>
                            <p>[MENUNGGU PEMILIK: nama layanan, nomor, atau tautan bantuan resmi]</p>
                        </div>
                    <?php endif; ?>

                    <div class="surat-penutup">
                        <!-- [MENUNGGU PEMILIK: kalimat penutup surat] -->
                        <p>[MENUNGGU PEMILIK: penutup surat]</p>
                    </div>
                </div>

                <div class="surat-kanan">
                    <div class="polaroid">
                        <?php if ($amplopId === 3): ?>
                            <!-- Foto terkunci di Surat 3 (F-13). [MENUNGGU PEMILIK: keputusan no. 4, kapan foto terbuka] -->
                            <div class="foto-terkunci">
                                <p class="foto-terkunci-ikon" aria-hidden="true">&#128274;</p>
                                <p>Foto ini masih terkunci</p>
                            </div>
                        <?php else: ?>
                            <!-- [MENUNGGU PEMILIK: foto polaroid final. Gambar ini hanya placeholder.] -->
                            <img src="/assets/images/polaroid.svg" alt="Placeholder foto polaroid" width="300" height="360">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="surat-navigasi">
                <a href="/amplop.php" class="tombol-secondary">Kembali ke amplop</a>
            </div>
        </div>
    </div>
</main>
<?php $scripts = ['surat.js']; require __DIR__ . '/partials/foot.php'; ?>
