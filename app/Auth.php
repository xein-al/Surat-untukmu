<?php
// Gerbang kode dan status sesi (AB-10, NF-S1).

class Auth {

    // Keputusan pemilik: 5 kali salah, dikunci selama 5 menit.
    // Catatan percobaan lebih dari 1 hari dihapus otomatis (lihat logAttempt).
    const BATAS_PERCOBAAN = 5;
    const JENDELA_MENIT = 5;

    // ---------- Status di sesi ----------

    public static function hasPassedGate() {
        return isset($_SESSION['passed_gate']) && $_SESSION['passed_gate'] === true;
    }

    public static function markGatePassed() {
        // ID sesi diganti saat status naik, supaya ID lama tidak bisa dipakai ulang.
        session_regenerate_id(true);
        $_SESSION['passed_gate'] = true;
    }

    public static function getRespondenId() {
        return isset($_SESSION['responden_id']) ? (int)$_SESSION['responden_id'] : null;
    }

    public static function setRespondenId($id) {
        $_SESSION['responden_id'] = (int)$id;
    }

    public static function hasResponden() {
        return self::getRespondenId() !== null;
    }

    public static function hasConsented() {
        return isset($_SESSION['consented']) && $_SESSION['consented'] === true;
    }

    public static function markConsented() {
        $_SESSION['consented'] = true;
    }

    // ---------- Penjaga halaman (dicek di server, AB-10) ----------

    public static function requireGate() {
        if (!self::hasPassedGate()) {
            Security::redirect('/index.php');
        }
    }

    // Nama ditahan di sesi sampai pengguna setuju (belum ada baris responden sebelum itu).
    public static function hasNamaTertunda() {
        return isset($_SESSION['nama']) && is_string($_SESSION['nama']) && $_SESSION['nama'] !== '';
    }

    public static function requireResponden() {
        self::requireGate();
        if (!self::hasResponden()) {
            Security::redirect(self::hasNamaTertunda() ? '/persetujuan.php' : '/nama.php');
        }
    }

    public static function requireConsent() {
        self::requireResponden();
        if (!self::hasConsented()) {
            Security::redirect('/persetujuan.php');
        }
    }

    // ---------- Pembatasan percobaan kode (F-02, NF-S6) ----------

    // Jendela waktu dihitung oleh database (NOW()), sama dengan yang mengisi waktu_percobaan.
    // Jika dihitung di PHP, zona waktu PHP dan database bisa berbeda dan batasnya tidak bekerja.
    public static function checkRateLimit() {
        $db = Database::getInstance();
        $row = $db->fetchOne(
            "SELECT COUNT(*) AS total FROM percobaan_kode
             WHERE pengenal_klien = ? AND berhasil = 0
               AND waktu_percobaan > DATE_SUB(NOW(), INTERVAL " . (int)self::JENDELA_MENIT . " MINUTE)",
            [Security::getClientIdentifier()]
        );
        return (int)($row['total'] ?? 0) < self::BATAS_PERCOBAAN;
    }

    public static function logAttempt($success) {
        // Catatan percobaan lebih dari 1 hari tidak berguna (jendela batas hanya menit) dan memuat hash IP.
        Database::getInstance()->execute("DELETE FROM percobaan_kode WHERE waktu_percobaan < DATE_SUB(NOW(), INTERVAL 1 DAY)");
        Database::getInstance()->execute(
            "INSERT INTO percobaan_kode (pengenal_klien, berhasil) VALUES (?, ?)",
            [Security::getClientIdentifier(), $success ? 1 : 0]
        );
    }

    public static function validateAccessCode($code) {
        $rows = Database::getInstance()->fetchAll("SELECT hash_kode FROM kode_akses");
        foreach ($rows as $row) {
            if (Security::verifyCode($code, $row['hash_kode'])) {
                return true;
            }
        }
        return false;
    }
}
