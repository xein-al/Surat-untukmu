<?php
// Penutup halaman. Variabel: $scripts (daftar nama file di /assets/js, opsional).
foreach (($scripts ?? []) as $s): ?>
<script src="/assets/js/<?= e($s) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
