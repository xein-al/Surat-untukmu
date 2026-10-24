<?php
// Salin berkas ini menjadi config/database.php lalu isi. database.php tidak boleh masuk repositori (NF-S9).
// Nilai di bawah SEMUA contoh. Jangan menulis kredensial asli di berkas ini.

return [
    'host'     => 'sqlXXX.infinityfree.com',   // dari panel hosting
    'database' => 'nama_database',
    'username' => 'nama_pengguna',
    'password' => 'isi_dari_panel_hosting',
    'charset'  => 'utf8mb4',
    // Garam acak untuk hash pengenal klien (pembatasan percobaan kode). Isi dengan teks acak panjang.
    'salt'     => 'ganti_dengan_teks_acak_yang_panjang',
];
