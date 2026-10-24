// Navigasi kartu pertanyaan (PRD bagian 6). Peningkatan saja: tanpa JavaScript, semua
// pertanyaan tampil berurutan dalam satu form dan tetap bisa dikirim. Pengiriman tetap
// satu form submit di akhir sesi, tanpa AJAX (AB-9).
(function () {
    'use strict';

    var form = document.querySelector('[data-kartu-form]');
    if (!form) { return; }

    var kartu = Array.prototype.slice.call(form.querySelectorAll('[data-kartu]'));
    var prev = form.querySelector('[data-prev]');
    var next = form.querySelector('[data-next]');
    var kirim = form.querySelector('[data-submit]');
    var status = form.querySelector('[data-status]');
    if (kartu.length < 2 || !prev || !next || !kirim) { return; }

    // Sesi 1: setiap pertanyaan wajib dipilih sebelum lanjut (AB-1, F-05).
    var wajib = form.getAttribute('data-wajib') === '1';
    var idx = 0;
    form.classList.add('js-kartu');

    function sudahDipilih(k) {
        return !!k.querySelector('input[type="radio"]:checked');
    }

    function semuaDipilih() {
        return kartu.every(sudahDipilih);
    }

    function pesan(teks) {
        if (status) { status.textContent = teks; }
    }

    function tampil(i, pindahFokus) {
        idx = Math.max(0, Math.min(kartu.length - 1, i));
        var terakhir = idx === kartu.length - 1;

        kartu.forEach(function (k, n) { k.hidden = (n !== idx); });

        prev.hidden = false;
        next.hidden = false;
        prev.disabled = (idx === 0);
        next.disabled = terakhir;
        kirim.hidden = !terakhir;

        perbarui();

        if (pindahFokus) {
            var kolom = kartu[idx].querySelector('textarea');
            var judul = kartu[idx].querySelector('h2');
            var sasaran = kolom || judul;
            if (sasaran) {
                if (!kolom) { sasaran.setAttribute('tabindex', '-1'); }
                sasaran.focus();
            }
        }
    }

    function perbarui() {
        if (!wajib) { return; }
        var ok = sudahDipilih(kartu[idx]);
        next.setAttribute('aria-disabled', ok ? 'false' : 'true');
        next.classList.toggle('belum-bisa', !ok);
        kirim.disabled = !semuaDipilih();
        // Jelaskan kenapa tombol kirim belum aktif.
        if (idx === kartu.length - 1 && !ok) {
            pesan('Pilih salah satu dulu ya, lalu lanjut.');
        }
    }

    function maju() {
        if (idx >= kartu.length - 1) { return; }
        if (wajib && !sudahDipilih(kartu[idx])) {
            pesan('Pilih salah satu dulu ya. Tidak ada jawaban yang salah.');
            return;
        }
        pesan('');
        tampil(idx + 1, true);
    }

    function mundur() {
        if (idx === 0) { return; }
        pesan('');
        tampil(idx - 1, true);
    }

    next.addEventListener('click', maju);
    prev.addEventListener('click', mundur);

    form.addEventListener('change', function () {
        pesan('');
        perbarui();
    });

    // Kirim hanya bila semua sudah dipilih (Sesi 1). Server tetap memeriksa ulang.
    form.addEventListener('submit', function (e) {
        if (wajib && !semuaDipilih()) {
            e.preventDefault();
            var belum = kartu.findIndex(function (k) { return !sudahDipilih(k); });
            pesan('Masih ada yang belum dipilih. Pilih yang paling dekat denganmu ya.');
            if (belum > -1) { tampil(belum, true); }
        }
    });

    // ← → pindah antar kartu. Tidak berlaku saat mengetik di kolom isian.
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') { return; }
        if (e.altKey || e.ctrlKey || e.metaKey || e.shiftKey) { return; }
        var t = e.target;
        var tag = t && t.tagName;
        if (tag === 'TEXTAREA' || tag === 'SELECT') { return; }
        if (tag === 'INPUT' && t.type !== 'radio') { return; }
        e.preventDefault();
        if (e.key === 'ArrowLeft') { mundur(); } else { maju(); }
    });

    // Penghitung karakter untuk kolom isian yang punya batas (supaya batas tidak terasa tiba-tiba).
    Array.prototype.forEach.call(form.querySelectorAll('textarea[maxlength]'), function (ta) {
        var maks = parseInt(ta.getAttribute('maxlength'), 10);
        var info = document.createElement('p');
        info.className = 'hitung-karakter';
        info.setAttribute('aria-hidden', 'true');
        function ubah() { info.textContent = ta.value.length + ' / ' + maks; }
        ta.addEventListener('input', ubah);
        ubah();
        ta.parentNode.insertBefore(info, ta.nextSibling);
    });

    tampil(0, false);
})();
