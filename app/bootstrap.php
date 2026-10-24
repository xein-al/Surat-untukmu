<?php
// Dimuat di awal SETIAP halaman. Urutan penting: handler error, HTTPS, sesi, header.

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Questions.php';
require_once __DIR__ . '/Jawaban.php';
require_once __DIR__ . '/Anjuran.php';
require_once __DIR__ . '/Flow.php';

ini_set('display_errors', '0');
ini_set('log_errors', '1');
date_default_timezone_set('Asia/Jakarta');
mb_internal_encoding('UTF-8');

// Singkatan untuk escape keluaran di template (NF-S4).
function e($value) {
    return Security::escape($value);
}

// Error tak terduga: catat di log, tampilkan halaman lembut tanpa detail teknis.
set_exception_handler(function ($ex) {
    error_log('Surat untukmu: ' . $ex);
    if (!headers_sent()) {
        http_response_code(500);
    }
    $judulHalaman = 'Ada kendala';
    require __DIR__ . '/../templates/error.php';
    exit;
});

Security::enforceHttps();

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    // Isian Sesi 2 bisa lama ditulis. Sesi yang habis di tengah jalan membuat jawaban hilang.
    ini_set('session.gc_maxlifetime', '7200');
    session_name('surat_sid');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => Security::isHttps(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

Security::setSecurityHeaders();
