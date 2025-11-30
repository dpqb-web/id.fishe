<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

$Template['title'] = 'Beranda';
$Template['description'] = '.';

$Template['header'] = '';

ob_start();
?>
<section></section>
<?php
$Template['main'] = ob_get_clean();

echo RenderTemplate();
?>
