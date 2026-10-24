<?php
// Urutan layar. Sumber kebenarannya database (jawaban yang sudah tersimpan),
// bukan tebakan dari sesi, supaya tidak ada layar yang bisa dilompati.

class Flow {

    public static function sesiSelesai($sesi) {
        // Sesi 3 yang dilewati lewat ✕ tidak menyimpan apa pun (AB-8), jadi dicatat di sesi saja.
        if ((int)$sesi === 3 && ($_SESSION['sesi3_lewat'] ?? false) === true) {
            return true;
        }
        return Jawaban::jumlahTersimpan($sesi) >= Questions::jumlah($sesi);
    }

    // Layar berikutnya yang sah untuk pengguna ini.
    public static function nextUrl() {
        if (!Auth::hasPassedGate())  return '/index.php';
        if (!Auth::hasResponden())   return Auth::hasNamaTertunda() ? '/persetujuan.php' : '/nama.php';
        if (!Auth::hasConsented())   return '/persetujuan.php';
        if (!self::sesiSelesai(1))   return '/sesi1.php';
        if (!self::sesiSelesai(2))   return '/sesi2.php';
        if (!self::sesiSelesai(3))   return '/sesi3.php';
        return '/amplop.php';
    }

    // Sesi 1 wajib (AB-1): tanpa Sesi 1 selesai, tidak ada jalan ke Sesi 2, Sesi 3, amplop, atau surat.
    public static function requireSesi1() {
        if (!self::sesiSelesai(1)) {
            Security::redirect('/sesi1.php');
        }
    }

    public static function requireSesi2() {
        self::requireSesi1();
        if (!self::sesiSelesai(2)) {
            Security::redirect('/sesi2.php');
        }
    }

    // Amplop dan surat hanya terbuka setelah Sesi 3 selesai atau dilewati.
    public static function requireSemuaSesi() {
        self::requireSesi2();
        if (!self::sesiSelesai(3)) {
            Security::redirect('/sesi3.php');
        }
    }
}
