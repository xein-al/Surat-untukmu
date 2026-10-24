<?php
// Isi nama (F-03). Nama hanya ditahan di sesi; baris responden baru dibuat setelah pengguna setuju.
require __DIR__ . '/../app/bootstrap.php';

Auth::requireGate();

if (Auth::hasResponden()) {
    Security::redirect(Flow::nextUrl());
}

$error = '';
$namaIsi = $_SESSION['nama'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = Security::postTeks('nama');
    $namaIsi = $nama ?? '';

    if (!Security::csrfOk()) {
        $error = 'Halaman ini sudah kedaluwarsa. Muat ulang dulu ya, lalu coba lagi.';
    } elseif ($nama === null || $nama === '') {
        // Nama "0" sah, jadi dibandingkan dengan '', bukan empty().
        $error = 'Isi namamu dulu ya.';
    } elseif (mb_strlen($nama) > 100) {
        $error = 'Namanya kepanjangan. Cukup nama panggilanmu saja ya.';
    } else {
        $_SESSION['nama'] = $nama;
        Security::redirect('/persetujuan.php');
    }
}

$judulHalaman = 'Isi nama';
require __DIR__ . '/../templates/nama.php';
