// Surat: Esc menutup modal dan kembali ke amplop (F-11).
(function () {
    'use strict';

    var tutup = document.querySelector('[data-tutup]');
    if (!tutup) { return; }

    // Fokus ke tombol tutup supaya pengguna keyboard dan pembaca layar langsung berada di dalam dialog.
    tutup.focus();

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            window.location.href = tutup.getAttribute('href');
        }
    });
})();
