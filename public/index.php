<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

$Template['title'] = 'Beranda';
$Template['description'] = '.';

ob_start();
?>
<style media="screen">
  header {
    display: none;
  }
</style>
<style media="screen and (max-width: 480px)">
  header {
    display: block;
  }
</style>
<?php
$Template['head'] = ob_get_clean();

ob_start();
?>
<form action="/" method="get">
  <input type="search" name="cari" placeholder="Cari barang..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" autocomplete="off">
  <button type="submit">
    <img src="/assets/img/bx/bx-search-alt.svg" alt="Cari" class="mono">
  </button>
</form>
<?php
$Template['header'] = ob_get_clean();

ob_start();
?>
<section></section>
<?php
$Template['main'] = ob_get_clean();

echo RenderTemplate();
?>
