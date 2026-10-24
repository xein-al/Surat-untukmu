<?php
// Sesi 1 · Kenali rasamu (F-05, AB-1, AB-3, AB-8, AB-9)
// 4 pertanyaan pilihan, wajib. Satu form submit di akhir sesi.
require __DIR__ . '/../app/bootstrap.php';

Auth::requireConsent();

$nama = $_SESSION['nama'] ?? 'Kamu';
$pertanyaan = Questions::sesi1();
$selesai = Flow::sesiSelesai(1);
$error = '';
$terpilih = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::csrfOk()) {
        $error = 'Halaman ini sudah kedaluwarsa. Muat ulang dulu ya, lalu coba lagi.';
    } elseif ($selesai) {
        Security::redirect('/sesi1.php');
    } else {
        $kiriman = (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) ? $_POST['jawaban'] : [];

        foreach ($pertanyaan as $nomor => $p) {
            $v = $kiriman[$nomor] ?? null;
            // Server hanya menerima A, B, C, atau D.
            if (is_string($v) && in_array($v, ['A', 'B', 'C', 'D'], true)) {
                $terpilih[$nomor] = $v;
            }
        }

        if (count($terpilih) < count($pertanyaan)) {
            $error = 'Masih ada yang belum dipilih. Pilih yang paling dekat denganmu ya. Tidak ada jawaban benar atau salah.';
        } else {
            Jawaban::simpan(1, 'pilihan', $terpilih);
            Security::redirect('/sesi1.php'); // muat ulang: tampil kartu selesai
        }
    }
}

$judulHalaman = 'Sesi 1 · Kenali rasamu';
require __DIR__ . '/../templates/sesi1.php';
