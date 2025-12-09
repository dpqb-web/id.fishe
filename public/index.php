<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

if (isset($_GET['cari'])) {
  if (empty($_GET['cari'])) {
    pesan(2, 'Kata kunci pencarian tidak boleh kosong.');
    ke();
  }
  $Template['title'] = 'Hasil pencarian dari "' . htmlspecialchars($_GET['cari']) . '"';
  $Template['description'] = 'Hasil pencarian dari "' . htmlspecialchars($_GET['cari']) . '"';

  $prep = $SQL->prepare('SELECT * FROM `barang` WHERE `nama` LIKE ? OR `keterangan` LIKE ?');
  $kata_kunci = '%' . $_GET['cari'] . '%';
  $prep->execute([$kata_kunci, $kata_kunci]);
  $barang2 = $prep->fetchAll();

  $prep2 = $SQL->prepare('SELECT * FROM `pengguna` WHERE `fullname` LIKE ? AND `penjual` = 1');
  $prep2->execute([$kata_kunci]);
  $penjual2 = $prep2->fetchAll();
} else {
  $Template['title'] = 'Beranda';
  $Template['description'] = 'Selamat datang di id.fishe, pasar daring untuk berbagai kebutuhan Anda.';

  $prep = $SQL->prepare('SELECT * FROM `barang` LIMIT 10');
  $prep->execute();
  $barang2 = $prep->fetchAll();
}

ob_start();
?>
<?php if (isset($_GET['cari'])) : ?>
  <section>
    <h1>Hasil pencarian dari "<?= htmlspecialchars($_GET['cari'] ?? '') ?>"</h1>
  </section>
  <section class="box">
    <div class="petak">
      <?php if ($penjual2) : ?>
        <?php foreach ($penjual2 as $penjual) : ?>
          <a href="/penjual.php?id=<?= urlencode($penjual['username']) ?>" class="item penjual">
            <h3><?= htmlspecialchars($penjual['fullname']) ?></h3>
            <p><?= htmlspecialchars($penjual['deskripsi'] ?? 'Penjual di id.fishe') ?></p>
          </a>
        <?php endforeach ?>
      <?php endif ?>
      <?php if ($barang2) : ?>
        <?php foreach ($barang2 as $buah) : ?>
          <?php include $DIR['TEMPLATE'] . 'buah.barang.php' ?>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </section>
<?php else : ?>
  <section>
    <h1>Barang-barang baru</h1>
  </section>
  <section class="petak">
    <?php foreach ($barang2 as $buah) : ?>
      <?php include $DIR['TEMPLATE'] . 'buah.barang.php' ?>
    <?php endforeach ?>
  </section>
<?php endif ?>
<?php
$Template['main'] = ob_get_clean();

require_once $DIR['TEMPLATE'] . 'index.php';
?>
