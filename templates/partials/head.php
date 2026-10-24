<?php
// Pembuka halaman. Variabel: $judulHalaman (wajib), $bodyClass (warna latar, opsional).
$bodyClass = $bodyClass ?? 'bg-pink';
$cssFile = __DIR__ . '/../../public/assets/css/style.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($judulHalaman) ?> · Surat untukmu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&amp;family=Nunito:wght@400;600;700&amp;family=Pacifico&amp;display=swap">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= (int)$cssVer ?>">
</head>
<body class="<?= e($bodyClass) ?>">
<a class="lewati-ke-isi" href="#isi">Lewati ke isi</a>
