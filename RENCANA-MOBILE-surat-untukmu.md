# Rencana Mobile Responsive: Surat untukmu

Dokumen ini merinci **Tahap 8** di `RENCANA-IMPLEMENTASI-surat-untukmu.md` dan **F-15** di `PRD-surat-untukmu.md`. Isinya urutan kerja dan kriteria uji. Tidak ada fitur baru. Hal yang belum diputuskan ditandai **[MENUNGGU PEMILIK]**.

- **Stack:** PHP 8, MySQL/MariaDB, HTML, CSS, JavaScript (tidak berubah)
- **Aturan kerja:** form submit biasa, tanpa AJAX. Teks 13 pertanyaan tidak diubah. Logika server (gerbang kode, CSRF, kotak anjuran) tidak disentuh.
- **Cakupan:** hanya tampilan dan interaksi sentuh. Tidak ada perubahan alur, data, atau aturan bisnis (AB-1 sampai AB-10).

---

## 1. Gerbang persetujuan (wajib sebelum mengerjakan)

PRD (bagian 3 dan F-15) menyatakan versi mobile **hanya dibuat setelah persetujuan eksplisit pemilik**, dan pertanyaan terbuka no. 12 belum terjawab.

- [ ] Pemilik menyatakan secara tertulis: "Mulai kerjakan versi mobile responsive." (simpan di issue GitHub atau catatan PRD)
- [ ] Jawab pertanyaan terbuka no. 12: apa yang dianggap "sudah ada di mobile", dan siapa pemberi persetujuan.
- [ ] Tambahkan keputusan di bagian 3 dokumen ini ke PRD bagian 11 (nomor 17 dan seterusnya).

Perencanaan boleh dilakukan sekarang. Pengerjaan kode menunggu centang di atas.

---

## 2. Kondisi kode saat ini (hasil pembacaan berkas)

Sudah ada: `<meta name="viewport">`, satu breakpoint `max-width: 760px` (sesuai PRD bagian 6), `prefers-reduced-motion`, target klik 44 px di sebagian besar tombol. Jadi ini **audit dan perapian**, bukan mulai dari nol.

| No. | Temuan | Lokasi | Dampak | Terkait |
|---|---|---|---|---|
| T-1 | Tombol panah mengecil jadi 40 px di mobile | `.panah` di `@media (max-width: 760px)` | Melanggar target klik minimal 44 px | NF-A1 |
| T-2 | Panah ada di samping kartu. Di layar 360 px, dua panah plus jarak menyisakan lebar kartu sempit | `.kartu-area` | Teks pertanyaan dan opsi menjadi sempit | PRD bagian 6 |
| T-3 | `min-height: 100vh` dan `max-height: 90vh/92vh` | `body`, `.modal-dialog-compact`, `.modal-surat` | Di browser mobile, `vh` ikut tinggi address bar sehingga bagian bawah modal bisa tertutup | F-07, F-11 |
| T-4 | Efek `:hover` (angkat tombol dan amplop) ikut aktif saat disentuh | `.tombol-*`, `.amplop`, `.opsi-pilihan` | Elemen "tersangkut" di keadaan hover setelah disentuh | NF-A6 |
| T-5 | Modal surat memakai padding 3rem desktop, `.tombol-tutup-surat` mutlak di pojok | `.modal-surat` | Pada layar kecil, area baca sempit dan tombol tutup bisa ikut tergulung | F-11 |
| T-6 | Polaroid diputar 2° | `.polaroid` | Bisa memicu geser horizontal di layar sempit | Tampilan |
| T-7 | Tiga amplop bertumpuk satu kolom, tiap amplop `min-height: 220px` | `.amplop-grid`, `.amplop` | Halaman Amplop menjadi panjang, amplop 3 jauh di bawah | F-09, F-10 |
| T-8 | Keyboard layar menutupi bagian modal Sesi 3 dan textarea | `.modal-dialog-compact` | Tombol Lanjut atau Kirim bisa tak terlihat saat mengetik | F-07 |
| T-9 | Tidak ada penanganan area aman (notch, bar gestur) | `head.php` | Konten terpotong di perangkat dengan notch | Tampilan |
| T-10 | Panah ← → hanya untuk keyboard. Di layar sentuh pengguna hanya punya tombol panah | `kartu.js` | Tidak masalah selama panah cukup besar (lihat T-1) | NF-A1 |
| T-11 | Font Pacifico, Nunito, Caveat dimuat dari Google Fonts | `head.php` | Pada jaringan seluler, teks bisa tampil dengan fallback dulu | NF-P (usulan) |

---

## 3. Keputusan yang dibutuhkan dari pemilik

Saya tidak mengasumsikan jawaban. Kolom "Usulan" hanya masukan, bukan keputusan.

| No. | Pertanyaan | Usulan (belum berlaku) | Memblokir |
|---|---|---|---|
| M-1 | Di mobile, di mana tombol panah ditaruh? PRD hanya menyebut "di samping kartu" untuk desktop. | Pindah ke bawah kartu, dua tombol berjajar, masing-masing ≥ 44 px (menyelesaikan T-1 dan T-2) | 8.2 |
| M-2 | Apakah geser (swipe) antar kartu diinginkan? Ini gestur baru yang tidak ada di PRD. | Tidak dikerjakan. Panah saja. | 8.2 |
| M-3 | Boleh menambah breakpoint untuk layar sangat kecil (misalnya ≤ 380 px), atau tetap satu breakpoint 760 px? | Tetap satu breakpoint, uji sampai 320 px | 8.1 |
| M-4 | Apakah target performa dan perangkat uji mobile di bagian 5 disetujui? | Disetujui | 8.5 |
| M-5 | Bagaimana jawaban yang belum terkirim jika pengguna berpindah aplikasi atau menutup tab? (pertanyaan terbuka no. 7 lebih berisiko di ponsel, karena aplikasi sering dimatikan sistem) | Tetap sesuai keputusan no. 7. Autosave tidak ditambahkan tanpa persetujuan. | 8.3 |
| M-6 | Tombol "Keluar" di layar Amplop tidak ada di PRD. Dipertahankan atau dihapus? (sudah ada catatan di `amplop.php`) | Keputusan pemilik | 8.2 |
| M-7 | Persetujuan orang tua/wali (pertanyaan terbuka no. 9). Pengguna di bawah 18 tahun kemungkinan besar memakai ponsel. | Wajib terjawab sebelum rilis mobile ke publik | Rilis |

---

## 4. Tahapan kerja

Satu tahap per cabang atau per PR agar mudah ditinjau. Setiap tahap selesai bila kriteria "Selesai jika" terpenuhi.

### 8.0 Persiapan
- [ ] Gerbang persetujuan di bagian 1 terpenuhi.
- [ ] Buat cabang `fitur/mobile-responsive` dari cabang utama.
- [ ] Ambil tangkapan layar semua layar di desktop sebagai pembanding "sebelum".
- [ ] Siapkan data uji: satu responden dengan jawaban yang memicu kotak anjuran, satu tanpa.

**Selesai jika:** cabang dibuat, pembanding desktop tersimpan di luar repositori atau di folder yang disepakati.

### 8.1 Fondasi CSS dan viewport (T-3, T-4, T-9)
- [ ] `head.php`: `viewport` ditambah `viewport-fit=cover`.
- [ ] Ganti `100vh` ke `100dvh` dengan `100vh` sebagai cadangan (`@supports`).
- [ ] Padding area aman: `env(safe-area-inset-*)` pada `.container` dan modal.
- [ ] Bungkus semua efek `:hover` dengan `@media (hover: hover)`.
- [ ] Tambah `overflow-x: clip` hanya jika masih ada geser horizontal setelah perbaikan penyebabnya.
- [ ] Pastikan semua `input` dan `textarea` berukuran font ≥ 16 px (agar iOS tidak memperbesar halaman saat fokus).

**Selesai jika:** tidak ada geser horizontal di 320 px pada layar mana pun; tampilan desktop identik dengan pembanding.

### 8.2 Layar masuk dan Sesi 1–3 (F-03, F-05, F-06, F-07, T-1, T-2)
- [ ] Panah: ukuran ≥ 44 × 44 px di semua lebar. Letak sesuai keputusan M-1.
- [ ] Opsi A–D: satu kolom di ≤ 760 px, tinggi ≥ 44 px, jarak antar opsi cukup agar tidak salah sentuh.
- [ ] Kartu: `padding` dan tinggi minimum dicek di 320 px, tanpa teks terpotong.
- [ ] Modal Sesi 3: tombol ✕ tetap ≥ 44 px dan tidak tertutup; isi modal bisa digulir; tombol Lanjut/Kirim tetap terjangkau saat keyboard layar terbuka (T-8).
- [ ] Textarea Sesi 2: tinggi awal wajar di ponsel, bisa digulir.
- [ ] Penghitung karakter dan pesan status (`role="status"`) tetap terlihat.

**Selesai jika:** keempat opsi tiap pertanyaan Sesi 1 bisa dipilih dengan satu sentuhan; Sesi 3 bisa diselesaikan dan dilewati lewat ✕ di ponsel; jumlah baris `jawaban` tetap 4 / 6 / 3 (0 jika ✕).

### 8.3 Amplop dan surat (F-09, F-10, F-11, F-12, F-13, T-5, T-6, T-7)
- [ ] Amplop: kurangi `min-height` di mobile agar tiga amplop dan penghitung "x dari 3 dibuka" terlihat tanpa terlalu banyak menggulir.
- [ ] Amplop 3 terkunci tetap menampilkan "Coming soon" dan tetap terbaca penuh (tanpa `opacity`).
- [ ] Surat: layout satu kolom, urutan teks lalu polaroid lalu penutup.
- [ ] Tombol tutup surat dibuat tetap terlihat saat menggulir (misalnya `position: sticky`), target ≥ 44 px.
- [ ] Polaroid: pastikan rotasi tidak membuat geser horizontal.
- [ ] Kotak anjuran (Surat 2): tampil penuh, **tidak dipotong, tidak disembunyikan, tidak di balik lipatan yang harus dibuka** (AB-6).
- [ ] Foto terkunci di Surat 3 tetap tampil sesuai keputusan no. 4.
- [ ] Esc tetap menutup surat di perangkat dengan keyboard; di sentuh, tombol tutup yang dipakai.

**Selesai jika:** kombinasi uji kotak anjuran di tahap 6.2 RENCANA-IMPLEMENTASI lolos di ponsel; kotak anjuran terbaca utuh tanpa interaksi tambahan.

### 8.4 Aksesibilitas di sentuh (NF-A1 sampai NF-A6)
- [ ] Semua target klik ≥ 44 px (ukur, jangan perkirakan).
- [ ] Kontras AA tetap terpenuhi pada semua latar.
- [ ] `role="radio"` dan `role="dialog"` tidak berubah.
- [ ] Uji pembaca layar bawaan (VoiceOver iOS, TalkBack Android) untuk satu alur penuh.
- [ ] Uji perbesaran teks sistem 200%: tidak ada teks terpotong atau tertimpa.
- [ ] `prefers-reduced-motion` tetap mematikan animasi.

**Selesai jika:** seluruh butir lolos pada perangkat uji bagian 5.

### 8.5 Performa mobile (NF-P, hanya bila M-4 disetujui)
- [ ] Ukur ukuran gambar per layar; pertahankan ≤ 1 MB *(usulan NF-P2)*.
- [ ] Font: tambah `font-display: swap` (sudah ada di URL Google Fonts) dan periksa fallback tampil layak.
- [ ] Opsional atas persetujuan pemilik: hosting font sendiri bila Google Fonts lambat. Dicek terhadap batas penyimpanan InfinityFree.
- [ ] Setiap layar selesai dimuat dalam 3 detik di 4G *(usulan, perlu konfirmasi M-4)*.

### 8.6 Pengujian dan rilis
- [ ] Jalankan seluruh uji tahap 6 RENCANA-IMPLEMENTASI di ponsel (fungsional, kotak anjuran, keamanan, data).
- [ ] Uji regresi desktop: bandingkan dengan tangkapan layar 8.0.
- [ ] Hapus data uji sebelum rilis.
- [ ] Gerbang rilis tahap 7 tetap berlaku, termasuk keputusan no. 9 (wali) dan R-1 (gambar karakter).
- [ ] Merge ke cabang utama lewat PR, lalu unggah ke InfinityFree.

---

## 5. Perangkat dan kriteria uji

Daftar ukuran adalah *(usulan)* sampai M-4 disetujui. Uji juga lewat mode perangkat di browser, tetapi setidaknya satu ponsel iOS dan satu Android asli.

| Ukuran (px) | Jenis |
|---|---|
| 320 × 568 | Ponsel terkecil |
| 360 × 640 | Android umum |
| 390 × 844 | iPhone umum |
| 412 × 915 | Android besar |
| 768 × 1024 | Tablet potret (di atas 760 px, layout desktop) |
| 667 × 375 | Ponsel lanskap |

Browser: Safari iOS dan Chrome Android, dua versi stabil terakhir *(usulan)*.

### Kriteria penerimaan (bisa diuji)

| ID | Kriteria | Cara uji |
|---|---|---|
| MB-1 | Tidak ada geser horizontal di setiap layar pada semua ukuran di atas | Di konsol: `document.documentElement.scrollWidth <= document.documentElement.clientWidth` bernilai benar |
| MB-2 | Semua elemen interaktif berukuran ≥ 44 × 44 px | Periksa `getBoundingClientRect()` tiap tombol, panah, opsi, dan tautan amplop |
| MB-3 | Semua `input` dan `textarea` berfont ≥ 16 px | Periksa `getComputedStyle`; di iOS halaman tidak membesar saat fokus |
| MB-4 | Sesi 1: tidak bisa lanjut sebelum keempat pertanyaan dipilih | Sentuh Lanjut tanpa memilih: pesan muncul, tidak berpindah |
| MB-5 | Sesi 2 semua kosong berhasil terkirim | 6 baris kosong tersimpan |
| MB-6 | Sesi 3: ✕ melewati seluruh sesi | 0 baris sesi 3, layar Amplop terbuka |
| MB-7 | Tombol kirim di Sesi 2 dan 3 tetap bisa dijangkau saat keyboard layar terbuka | Uji di perangkat asli |
| MB-8 | Amplop 3 menampilkan "Coming soon" sampai amplop 1 dan 2 dibuka | Sentuh amplop 3 lebih awal: tidak terbuka |
| MB-9 | Kotak anjuran tampil penuh pada semua kombinasi di tabel 6.2 RENCANA-IMPLEMENTASI | Tidak ada teks terpotong; tidak perlu interaksi tambahan |
| MB-10 | Tombol tutup surat selalu terlihat saat surat digulir | Gulir sampai paling bawah |
| MB-11 | Teks 13 pertanyaan identik dengan PRD bagian 4 | Bandingkan per karakter; tidak ada perubahan |
| MB-12 | Tidak ada perubahan pada berkas `app/` dan `database.sql` | `git diff --stat` pada cabang |
| MB-13 | Tampilan desktop tidak berubah | Bandingkan dengan tangkapan layar 8.0 |

---

## 6. Perubahan PRD yang diusulkan

Setelah pemilik menyetujui, perbarui `PRD-surat-untukmu.md`:

1. **Bagian 3:** pindahkan "Versi responsive mobile" dari "Tidak termasuk" ke "Termasuk" untuk rilis berikutnya, dengan catatan tanggal persetujuan.
2. **F-15:** ganti kriteria menjadi MB-1 sampai MB-13 di atas.
3. **Bagian 6:** tambahkan spesifikasi tata letak mobile (letak panah sesuai M-1, opsi satu kolom, amplop satu kolom).
4. **Bagian 7:** tambahkan target performa dan daftar perangkat mobile bila M-4 disetujui.
5. **Bagian 11:** tambahkan M-1 sampai M-7 sebagai pertanyaan terbuka nomor 17 dan seterusnya.

---

## 7. Sebelum push ke GitHub

- [ ] Pastikan `config/database.php` **tidak** ikut. `.gitignore` sudah memuatnya, tetapi periksa dengan `git status` dan `git ls-files config/` sebelum commit pertama. Yang boleh ikut hanya `database.example.php`.
- [ ] Jika kredensial pernah ikut ter-commit, ganti kata sandi database di InfinityFree, karena menghapus berkas saja tidak menghapus riwayat commit. *(NF-S9)*
- [ ] Pastikan tidak ada kode bersama asli, data responden, atau berkas `*.log` di repositori.
- [ ] Jika repositori publik: pertimbangkan membuatnya **privat**, karena memuat struktur data tentang cerita emosional pengguna dan logika kotak anjuran.
- [ ] Alur cabang: `main` untuk versi desktop yang sudah stabil, `fitur/mobile-responsive` untuk pekerjaan ini, digabung lewat PR setelah 8.6 lolos.
- [ ] Pesan commit per tahap, misalnya `mobile 8.1: viewport dan dvh`, `mobile 8.2: panah 44px dan opsi satu kolom`.

---

## 8. Risiko

| Risiko | Dampak | Penanganan |
|---|---|---|
| Panah dipindah tanpa keputusan pemilik | Menyimpang dari PRD | Tunggu M-1 |
| Perubahan CSS merusak tampilan desktop | Regresi | Tangkapan layar pembanding (8.0), MB-13 |
| Kotak anjuran tersembunyi atau terpotong di layar kecil | Melanggar AB-6, pengguna dalam kondisi berat tidak melihat anjuran | MB-9, uji manual tiap kombinasi |
| Jawaban hilang karena aplikasi ponsel dimatikan sistem | Pengguna kehilangan isian | Keputusan no. 7 dan M-5 |
| Rilis ke pengguna di bawah 18 tahun tanpa mekanisme wali | Risiko kepatuhan (UU PDP, perlu dipastikan ke penasihat hukum) | Keputusan no. 9 sebagai gerbang rilis |
| Kredensial database ikut ke GitHub | Kebocoran data sensitif | Bagian 7 |
