<?php
// Uji aturan kotak anjuran (AB-5, AB-7) pada seluruh 4x4x4x4 = 256 kombinasi jawaban.
//     php tests/anjuran-test.php
// Tidak butuh database.

require __DIR__ . '/../app/Anjuran.php';

$gagal = 0;
$total = 0;
$huruf = ['A', 'B', 'C', 'D'];

foreach ($huruf as $tidur) {
    foreach ($huruf as $lelah) {
        foreach ($huruf as $butuh) {
            foreach ($huruf as $sendirian) {
                $total++;
                // Tabel acuan, ditulis terpisah dari kode: tampil jika SALAH SATU benar.
                $harapan = ($tidur === 'D') || ($lelah === 'C' || $lelah === 'D') || ($sendirian === 'C' || $sendirian === 'D');
                $hasil = Anjuran::perluTampil([1 => $tidur, 2 => $lelah, 3 => $butuh, 4 => $sendirian]);

                if ($hasil !== $harapan) {
                    $gagal++;
                    echo "GAGAL: tidur=$tidur lelah=$lelah butuh=$butuh sendirian=$sendirian\n";
                }
            }
        }
    }
}

// Pertanyaan no. 3 tidak boleh berpengaruh (AB-7).
foreach ($huruf as $tidur) foreach ($huruf as $lelah) foreach ($huruf as $sendirian) {
    $hasilA = Anjuran::perluTampil([1 => $tidur, 2 => $lelah, 3 => 'A', 4 => $sendirian]);
    foreach ($huruf as $butuh) {
        if (Anjuran::perluTampil([1 => $tidur, 2 => $lelah, 3 => $butuh, 4 => $sendirian]) !== $hasilA) {
            $gagal++;
            echo "GAGAL: no. 3 memengaruhi hasil ($tidur/$lelah/$butuh/$sendirian)\n";
        }
    }
}

echo $gagal === 0 ? "LULUS: $total kombinasi sesuai aturan.\n" : "$gagal kegagalan dari $total kombinasi.\n";
exit($gagal === 0 ? 0 : 1);
