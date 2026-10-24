<?php
// Amplop (F-09, F-10, AB-2)
require __DIR__ . '/../app/bootstrap.php';

Auth::requireConsent();
Flow::requireSemuaSesi();

$nama = $_SESSION['nama'] ?? 'Kamu';

// Amplop yang sudah dibuka disimpan di sesi.
// [MENUNGGU PEMILIK: keputusan no. 4, perilaku saat pengguna kembali atau sesi baru]
$amplopBuka = array_values(array_unique(array_map('intval', $_SESSION['amplop_buka'] ?? [])));
$jumlahBuka = count($amplopBuka);

// Amplop 3 terkunci sampai amplop 1 dan 2 dibuka (AB-2).
$amplop3Terkunci = !(in_array(1, $amplopBuka, true) && in_array(2, $amplopBuka, true));

$judulHalaman = 'Amplop';
require __DIR__ . '/../templates/amplop.php';
