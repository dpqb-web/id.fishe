<?php
require_once __DIR__ . '/../index.php';

$TEMPLATE['title'] = 'Beranda';
$TEMPLATE['description'] = 'Selamat datang di situs web contoh yang dibuat dengan Fishe Framework.';

buatPesan(0, 'tes');

ob_start();
?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('nav a[href="/"]').classList.add('aktif');
  });
</script>
<?php
$TEMPLATE['head'] = ob_get_clean();

ob_start();
?>
<?= var_dump($_COOKIE) ?>
<?php
$TEMPLATE['main'] = ob_get_clean();

echo render();
?>
