<?php
// Gerbang kode (F-01, F-02)
require __DIR__ . '/../app/bootstrap.php';

// Sudah lolos: lanjutkan dari layar yang sah, bukan selalu dari awal.
if (Auth::hasPassedGate()) {
    Security::redirect(Flow::nextUrl());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = Security::postTeks('code');

    if (!Security::csrfOk()) {
        $error = 'Halaman ini sudah kedaluwarsa. Muat ulang dulu ya, lalu coba lagi.';
    } elseif ($code === null || $code === '') {
        $error = 'Masukkan kodenya dulu ya.';
    } elseif (!Auth::checkRateLimit()) {
        $error = 'Sudah beberapa kali dicoba. Istirahat sebentar, lalu coba lagi nanti ya.';
    } elseif (Auth::validateAccessCode($code)) {
        Auth::logAttempt(true);
        Auth::markGatePassed();
        Security::redirect(Flow::nextUrl());
    } else {
        Auth::logAttempt(false);
        $error = 'Kodenya belum tepat. Coba lagi ya.';
    }
}

$judulHalaman = 'Masukkan kode';
require __DIR__ . '/../templates/gerbang.php';
