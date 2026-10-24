# Surat untukmu

Pengalaman interaktif yang hangat untuk perempuan: tiga sesi pertanyaan, lalu tiga surat (dulu, sekarang, nanti).
Sumber kebenaran: `PRD-surat-untukmu.md` dan `RENCANA-IMPLEMENTASI-surat-untukmu.md`.

**Stack:** PHP 8 (butuh `mbstring` dan `pdo_mysql`), MySQL/MariaDB, HTML, CSS, JavaScript. Tanpa AJAX. Platform rilis ini: desktop.

## Struktur
```
public/       halaman yang bisa diakses (index, nama, persetujuan, sesi1-3, amplop, surat) + assets
app/          logika: bootstrap, Security, Auth, Flow (urutan layar), Jawaban, Anjuran, Questions (13 teks pertanyaan)
templates/    tampilan (+ partials/)
config/       database.php (TIDAK masuk repositori), database.example.php
tools/        buat-hash.php (hash kode bersama), cek-rilis.php (gerbang rilis)
tests/        anjuran-test.php
database.sql  skema
```

## Setup
1. Impor `database.sql` lewat phpMyAdmin.
2. Salin `config/database.example.php` menjadi `config/database.php`, isi dari panel hosting. Isi juga `salt` dengan teks acak.
3. Di komputermu sendiri: `php tools/buat-hash.php`, ketik kode bersama, jalankan `INSERT` yang dicetak di phpMyAdmin.
4. Lokal: `php -S localhost:8000 -t public`.

## Deploy ke InfinityFree
Document root-nya `htdocs` dan tidak bisa diarahkan ke `public/`. Unggah **seluruh proyek** ke `htdocs`.
`.htaccess` di akar mengalihkan semua permintaan ke `public/` dan menutup `app/`, `config/`, `templates/`, `tools/`, `tests/`.
Belum diuji di Apache sungguhan; uji dulu bahwa `/app/Auth.php`, `/config/database.php`, `/database.sql` memberi 403/404.

## Pengecekan
- `php tests/anjuran-test.php` : 256 kombinasi aturan kotak anjuran.
- `php tools/cek-rilis.php` : gagal selama masih ada `[MENUNGGU PEMILIK]`. Harus lulus sebelum rilis.
- `TESTING-CHECKLIST.md` : uji manual tahap 6.

## Menunggu pemilik
Placeholder `[MENUNGGU PEMILIK]`: isi Surat 1-3 dan penutup, foto polaroid, teks dan kontak kotak anjuran, tiga bagian teks persetujuan, kredensial database, batas percobaan kode (`Auth::BATAS_PERCOBAAN`, `JENDELA_MENIT`), serta pertanyaan terbuka PRD bagian 11.
