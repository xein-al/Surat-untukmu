<?php
// Keamanan: CSRF, escape keluaran, hash kode, header, HTTPS.

class Security {

    // ---------- CSRF (NF-S5) ----------

    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Token dari klien bisa berupa array bila dimanipulasi, jadi tipenya dicek dulu.
    public static function validateCsrfToken($token) {
        if (!is_string($token) || empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function csrfOk() {
        return self::validateCsrfToken($_POST['csrf_token'] ?? null);
    }

    public static function getCsrfField() {
        return '<input type="hidden" name="csrf_token" value="' . self::escape(self::generateCsrfToken()) . '">';
    }

    // ---------- Escape keluaran (NF-S4) ----------

    public static function escape($value) {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    // ---------- Input ----------

    // Ambil teks dari nilai yang dikirim klien. Mengembalikan null bila bukan string atau
    // bukan UTF-8 yang valid. Baris baru diseragamkan ke "\n" (browser mengirim "\r\n"),
    // supaya hitungan panjang sama dengan yang dilihat pengguna.
    public static function bersihkanTeks($value) {
        if (!is_string($value) || !mb_check_encoding($value, 'UTF-8')) {
            return null;
        }
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        return trim($value);
    }

    public static function postTeks($key) {
        return self::bersihkanTeks($_POST[$key] ?? null);
    }

    // ---------- Kode akses (NF-S2) ----------

    public static function hashCode($code) {
        return password_hash($code, PASSWORD_DEFAULT);
    }

    public static function verifyCode($code, $hash) {
        return password_verify($code, $hash);
    }

    // Pengenal klien untuk pembatasan percobaan: hash IP saja (bukan IP + User-Agent,
    // karena User-Agent bisa diganti-ganti untuk menghindari batas).
    public static function getClientIdentifier() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return hash('sha256', Database::config('salt', '') . $ip);
    }

    // ---------- Pengalihan, HTTPS, header ----------

    public static function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    public static function isHttps() {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }
        if (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') {
            return true;
        }
        return (string)($_SERVER['SERVER_PORT'] ?? '') === '443';
    }

    public static function isLocal() {
        $host = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
        return in_array($host, ['localhost', '127.0.0.1', '[::1]'], true);
    }

    // HTTP dialihkan ke HTTPS (NF-S7). Host divalidasi supaya header Host palsu
    // tidak bisa dipakai mengarahkan pengguna ke situs lain.
    public static function enforceHttps() {
        if (self::isLocal() || self::isHttps()) {
            return;
        }
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (!preg_match('/^[a-z0-9.\-]+(:\d+)?$/i', $host)) {
            http_response_code(400);
            exit;
        }
        $uri = preg_replace('/[\r\n]/', '', $_SERVER['REQUEST_URI'] ?? '/');
        header('Location: https://' . $host . $uri, true, 301);
        exit;
    }

    // noindex (NF-S8) dan header pengaman lain. Halaman memuat data pribadi,
    // jadi tidak boleh disimpan di cache browser atau proxy.
    public static function setSecurityHeaders() {
        header('X-Robots-Tag: noindex, nofollow');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: no-referrer');
        header('Cache-Control: no-store');
        // Tidak ada script atau style inline. Font dari Google Fonts.
        // Jika hosting menyuntikkan skrip sendiri dan halaman jadi rusak, longgarkan di sini.
        header("Content-Security-Policy: default-src 'self'; script-src 'self'; "
            . "style-src 'self' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; "
            . "img-src 'self' data:; form-action 'self'; base-uri 'none'; frame-ancestors 'none'");
    }
}
