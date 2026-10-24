# Checklist Tahap 6: Pengujian
Sebelum rilis, centang semua item ini.

## 6.1 Fungsional
- [ ] Kode benar → masuk ke Isi nama
- [ ] Kode salah 5x → ditolak sementara; coba lagi setelah 5 menit → bisa
- [ ] Nama kosong → "Isi namamu dulu ya."
- [ ] Buka `/sesi1.php` tanpa persetujuan → redirect
- [ ] Sesi 1 tidak lengkap → tidak bisa submit
- [ ] Sesi 2 semua kosong → berhasil, 6 baris kosong di DB
- [ ] Sesi 3 klik ✕ → 0 baris sesi 3, langsung ke amplop
- [ ] Amplop 3 sebelum 1&2 → "Coming soon", tidak buka
- [ ] Esc di modal surat → modal tutup

## 6.2 Kotak Anjuran (Surat 2)
Test kombinasi jawaban sesi 1:

| Tidur | Lelah | Sendirian | Kotak? |
|-------|-------|-----------|--------|
| A/B/C | A/B   | A/B       | ✗      |
| D     | any   | any       | ✓      |
| any   | C     | any       | ✓      |
| any   | D     | any       | ✓      |
| any   | any   | C         | ✓      |
| any   | any   | D         | ✓      |

- [ ] Semua kombinasi di atas benar

## 6.2b Alur dan ketahanan (baru)
- [ ] Buka `/sesi2.php`, `/sesi3.php`, `/amplop.php`, `/surat.php?a=1` langsung setelah persetujuan (Sesi 1 belum diisi) → dialihkan ke `/sesi1.php`
- [ ] Kirim Sesi 1 dua kali (klik ganda / tombol Back lalu kirim) → tidak error, baris di `jawaban` tetap 4
- [ ] Kirim Sesi 1 dengan 3 dari 4 terpilih (hapus JS di DevTools) → ditolak server, pilihan tetap terisi
- [ ] Kirim `jawaban=ABCD` (bukan array) atau `csrf_token[]=x` → tidak ada error 500
- [ ] Isi nama lalu tutup tab di layar persetujuan → tabel `responden` TIDAK bertambah (nama baru tersimpan setelah setuju)
- [ ] Tempel teks 20.000 karakter di satu isian Sesi 2 → tersimpan utuh
- [ ] Nama `0` → diterima; nama spasi saja → "Isi namamu dulu ya."
- [ ] Salah kode 5x lalu ganti User-Agent → tetap ditolak (pembatasan memakai IP saja)
- [ ] Zona waktu database ≠ PHP: batas percobaan tetap bekerja
- [ ] Selesai Sesi 1/2 → muncul kartu selesai; Sesi 3 selesai → kartu "Suratmu menunggu"
- [ ] `/app/Auth.php`, `/config/database.php`, `/database.sql`, `/README.md` → 403/404 di hosting
- [ ] `php tests/anjuran-test.php` lulus; `php tools/cek-rilis.php` lulus (sebelum rilis)

## 6.3 Keamanan
- [ ] Akses `/sesi1.php` tanpa sesi → redirect `/index.php`
- [ ] Akses `/persetujuan.php` tanpa responden → redirect `/nama.php`
- [ ] Input `' OR 1=1 --` di nama → tidak ubah query
- [ ] Input `<script>alert(1)</script>` di nama → tampil sebagai teks
- [ ] Submit form tanpa CSRF token → ditolak
- [ ] Submit sesi 1 dengan nilai `X` (bukan A-D) → ditolak server
- [ ] Cek repositori: tidak ada kredensial di `.git`
- [ ] Buka HTTP → redirect HTTPS
- [ ] Cek header: `X-Robots-Tag: noindex`
- [ ] Cek `/robots.txt` → Disallow all

## 6.4 Aksesibilitas
- [ ] Tab melalui semua elemen → fokus terlihat
- [ ] ← → pindah kartu (kecuali saat mengetik di kolom isian); ↑ ↓ mengganti opsi
- [ ] Esc di modal surat → tutup
- [ ] Semua tombol ≥ 44×44 px
- [ ] Kontras teks ≥ 4.5:1 (cek dengan tool)
- [ ] Browser DevTools → enable `prefers-reduced-motion` → animasi mati

## 6.5 Browser (2 versi stabil terakhir)
- [ ] Chrome desktop
- [ ] Edge desktop
- [ ] Firefox desktop
- [ ] Safari desktop (jika Mac tersedia)

## 6.6 Data
- [ ] Periksa tabel `jawaban`: tidak ada kolom skor/benar/salah
- [ ] Periksa tabel `kode_akses`: tidak ada kode plaintext
- [ ] Jawaban kosong tersimpan sebagai string kosong, bukan NULL
- [ ] Responden baru dapat ID auto-increment

## 6.7 Performa
- [ ] Total size gambar per halaman < 1 MB
- [ ] Halaman load < 3 detik (4G desktop)

## Gerbang Rilis (Tahap 7)
Jangan deploy sebelum semua ini siap:
- [ ] Konten surat 1, 2, 3 final dari pemilik
- [ ] Foto polaroid final
- [ ] Teks kotak anjuran dan kontak
- [ ] Teks persetujuan final (siapa baca, lama simpan, cara hapus)
- [ ] Keputusan persetujuan wali (atau konfirmasi tidak perlu)
- [ ] Gambar karakter legal (orisinal atau berlisensi)
- [ ] Kode akses final dan hash tersimpan
- [ ] Semua test di atas pass
- [ ] Rencana backup data oleh pemilik
