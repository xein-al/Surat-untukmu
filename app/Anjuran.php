<?php
// Aturan kotak anjuran di Surat 2 (F-12, AB-5, AB-6, AB-7).

class Anjuran {

    // Tampil jika SALAH SATU benar:
    //   tidur (no. 1) = D, atau lelah (no. 2) = C/D, atau sendirian (no. 4) = C/D.
    // Pertanyaan no. 3 sengaja tidak dipakai (AB-7).
    public static function perluTampil(array $sesi1) {
        $tidur = $sesi1[1] ?? null;
        $lelah = $sesi1[2] ?? null;
        $sendirian = $sesi1[4] ?? null;

        return $tidur === 'D'
            || in_array($lelah, ['C', 'D'], true)
            || in_array($sendirian, ['C', 'D'], true);
    }
}
