<?php
// Surat (F-11, F-12, F-13, AB-2, AB-5)
require __DIR__ . '/../app/bootstrap.php';

Auth::requireConsent();
Flow::requireSemuaSesi();

$nama = $_SESSION['nama'] ?? 'Kamu';
$amplopId = (int)($_GET['a'] ?? 0);

if (!in_array($amplopId, [1, 2, 3], true)) {
    Security::redirect('/amplop.php');
}

$sudahBuka = array_map('intval', $_SESSION['amplop_buka'] ?? []);

// Surat 3 hanya bisa dibuka setelah amplop 1 dan 2 (AB-2). Dicek di server, bukan hanya disembunyikan.
if ($amplopId === 3 && !(in_array(1, $sudahBuka, true) && in_array(2, $sudahBuka, true))) {
    Security::redirect('/amplop.php');
}

if (!in_array($amplopId, $sudahBuka, true)) {
    $sudahBuka[] = $amplopId;
}
$_SESSION['amplop_buka'] = $sudahBuka;

// Kotak anjuran hanya di Surat 2. Dibaca dari jawaban Sesi 1 yang tersimpan (AB-5, AB-7).
$tampilKotakAnjuran = ($amplopId === 2) && Anjuran::perluTampil(Jawaban::pilihanSesi1());

// [MENUNGGU PEMILIK: isi surat 1, 2, 3 final. Jangan diisi dengan karangan sendiri.]
$isiSurat = [
    1 => '[MENUNGGU PEMILIK: isi Surat 1 (dulu)]',
    2 => '[MENUNGGU PEMILIK: isi Surat 2 (sekarang)]',
    3 => '[MENUNGGU PEMILIK: isi Surat 3 (nanti)]',
];
$judulSurat = [1 => 'Dulu', 2 => 'Sekarang', 3 => 'Nanti'];

$judulHalaman = 'Surat ' . $amplopId;
require __DIR__ . '/../templates/surat.php';
