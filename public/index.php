<?php
require_once __DIR__ . '/../index.php';

$TEMPLATE['title'] = 'Beranda';
$TEMPLATE['description'] = 'Selamat datang di situs web contoh yang dibuat dengan Fishe Framework.';

ob_start();
?>
<?php
$TEMPLATE['main'] = ob_get_clean();

render();
?>
