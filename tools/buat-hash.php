<?php
// Membuat hash kode bersama untuk tabel kode_akses (NF-S2).
// Jalankan di komputermu sendiri, BUKAN di server web:
//     php tools/buat-hash.php
// Kodenya diketik saat diminta, jadi tidak tersimpan di berkas atau riwayat perintah.

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

fwrite(STDOUT, "Ketik kode bersama, lalu Enter: ");
$kode = trim((string)fgets(STDIN));

if ($kode === '') {
    fwrite(STDERR, "Kode kosong. Dibatalkan.\n");
    exit(1);
}

$hash = password_hash($kode, PASSWORD_DEFAULT);

if (!password_verify($kode, $hash)) {
    fwrite(STDERR, "Hash gagal diverifikasi. Coba lagi.\n");
    exit(1);
}

fwrite(STDOUT, "\nJalankan di phpMyAdmin (tab SQL):\n\n");
fwrite(STDOUT, "INSERT INTO kode_akses (hash_kode) VALUES ('" . $hash . "');\n\n");
fwrite(STDOUT, "Untuk mengganti kode: hapus baris lama di tabel kode_akses, lalu masukkan yang baru.\n");
