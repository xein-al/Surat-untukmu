<?php
// Sesi 3 · Sedikit lebih dekat (F-07, AB-1, AB-8, AB-9)
// 3 pertanyaan di pop up (tampilan saja). ✕ melewati seluruh sesi: tidak ada data Sesi 3 tersimpan.
require __DIR__ . '/../app/bootstrap.php';

Auth::requireConsent();
Flow::requireSesi2();

// Dilewati lewat ✕: langsung ke Amplop (F-07).
if (($_SESSION['sesi3_lewat'] ?? false) === true) {
    Security::redirect('/amplop.php');
}

$nama = $_SESSION['nama'] ?? 'Kamu';
$pertanyaan = Questions::sesi3();
$selesai = Flow::sesiSelesai(3);
$error = '';
$isian = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::csrfOk()) {
        $error = 'Halaman ini sudah kedaluwarsa. Muat ulang dulu ya, lalu coba lagi.';
    } elseif ($selesai) {
        Security::redirect('/sesi3.php');
    } elseif (isset($_POST['lewati'])) {
        // Tidak ada yang disimpan dari Sesi 3 (AB-8).
        $_SESSION['sesi3_lewat'] = true;
        Security::redirect('/amplop.php');
    } else {
        $kiriman = (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) ? $_POST['jawaban'] : [];
        $valid = true;
        $kepanjangan = false;

        foreach ($pertanyaan as $nomor => $teks) {
            $v = Security::bersihkanTeks($kiriman[$nomor] ?? '');
            if ($v === null) {
                $valid = false;
                $v = '';
            }
            if (mb_strlen($v) > Questions::MAKS_ISIAN) {
                $kepanjangan = true;
            }
            $isian[$nomor] = $v;
        }

        if (!$valid) {
            $error = 'Ada bagian yang tidak terbaca. Coba ketik ulang ya.';
        } elseif ($kepanjangan) {
            $error = 'Jawabanmu terlalu panjang untuk dikirim sekaligus. Coba dibagi ke pertanyaan lain ya.';
        } else {
            Jawaban::simpan(3, 'isian', $isian);
            Security::redirect('/sesi3.php'); // muat ulang: tampil kartu "Suratmu menunggu"
        }
    }
}

$judulHalaman = 'Sesi 3 · Sedikit lebih dekat';
require __DIR__ . '/../templates/sesi3.php';
