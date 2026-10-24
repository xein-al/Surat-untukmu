<?php
// Gerbang rilis (RENCANA tahap 7): gagal bila masih ada placeholder [MENUNGGU PEMILIK].
//     php tools/cek-rilis.php
// Placeholder tidak boleh ikut ke rilis publik.

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$akar = dirname(__DIR__);
$ditemukan = [];

$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($akar, FilesystemIterator::SKIP_DOTS));
foreach ($iter as $berkas) {
    $path = $berkas->getPathname();
    $rel = ltrim(str_replace($akar, '', $path), '/\\');
    // Alat, dokumentasi, dan tes sendiri tidak dihitung.
    if (!preg_match('/\.(php|svg|js|css|html)$/', $path)) { continue; }
    if (preg_match('#^(tools|tests|config)[/\\\\]#', $rel)) { continue; }

    foreach (file($path) as $no => $baris) {
        if (strpos($baris, '[MENUNGGU PEMILIK') !== false) {
            $ditemukan[] = $rel . ':' . ($no + 1) . '  ' . trim($baris);
        }
    }
}

if ($ditemukan) {
    fwrite(STDOUT, "BELUM BOLEH RILIS. " . count($ditemukan) . " placeholder tersisa:\n\n");
    fwrite(STDOUT, implode("\n", $ditemukan) . "\n");
    exit(1);
}

fwrite(STDOUT, "Tidak ada placeholder tersisa.\n");
exit(0);
