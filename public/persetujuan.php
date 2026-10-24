<?php
// Persetujuan (F-04, F-14). Data baru disimpan SETELAH pengguna setuju.
require __DIR__ . '/../app/bootstrap.php';

Auth::requireGate();

if (Auth::hasResponden() && Auth::hasConsented()) {
    Security::redirect(Flow::nextUrl());
}
if (!Auth::hasNamaTertunda()) {
    Security::redirect('/nama.php');
}

$nama = $_SESSION['nama'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::csrfOk()) {
        $error = 'Halaman ini sudah kedaluwarsa. Muat ulang dulu ya, lalu coba lagi.';
    } else {
        $db = Database::getInstance();
        $db->execute(
            "INSERT INTO responden (nama, status_persetujuan, waktu_persetujuan) VALUES (?, 'setuju', NOW())",
            [$nama]
        );
        Auth::setRespondenId($db->lastInsertId());
        Auth::markConsented();
        Security::redirect('/sesi1.php');
    }
}

$judulHalaman = 'Tentang datamu';
require __DIR__ . '/../templates/persetujuan.php';
