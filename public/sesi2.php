<?php
// Sesi 2 · Cerita hari ini (F-06, AB-1, AB-8, AB-9)
// 6 pertanyaan isian. Semua boleh dikosongkan; kosong disimpan sebagai kosong.
require __DIR__ . '/../app/bootstrap.php';

Auth::requireConsent();
Flow::requireSesi1();

$nama = $_SESSION['nama'] ?? 'Kamu';
$pertanyaan = Questions::sesi2();
$selesai = Flow::sesiSelesai(2);
$error = '';
$isian = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::csrfOk()) {
        $error = 'Halaman ini sudah kedaluwarsa. Muat ulang dulu ya, lalu coba lagi.';
    } elseif ($selesai) {
        Security::redirect('/sesi2.php');
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
            Jawaban::simpan(2, 'isian', $isian);
            Security::redirect('/sesi2.php'); // muat ulang: tampil kartu selesai
        }
    }
}

$judulHalaman = 'Sesi 2 · Cerita hari ini';
require __DIR__ . '/../templates/sesi2.php';
