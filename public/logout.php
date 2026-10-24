<?php
// Keluar. Lewat POST + token CSRF, supaya tidak bisa dipicu dari halaman lain.
// CATATAN: tombol Keluar tidak ada di PRD. Lihat laporan: perlu keputusan pemilik.
require __DIR__ . '/../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Security::csrfOk()) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

Security::redirect('/index.php');
