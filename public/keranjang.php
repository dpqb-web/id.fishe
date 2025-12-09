<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

periksaMasuk();

$prep = $SQL->prepare('SELECT * FROM `keranjang` WHERE `pembeli` = ?');
$prep->execute([$_SESSION['username']]);
$barang2 = $prep->fetchAll();

if (isset($_POST['pesan'])) {
  $prep = $SQL->prepare('INSERT INTO `pesanan` (`pembeli`, `barang`, `jumlah`) VALUES (?, ?, ?)');
  foreach ($barang2 as $b) {
    $prep->execute([$_SESSION['username'], $b['barang'], $b['jumlah']]);
  }
  $prep = $SQL->prepare('DELETE FROM `keranjang` WHERE `pembeli` = ?');
  $prep->execute([$_SESSION['username']]);

  pesan(0, 'Pesanan Anda telah dibuat.');
  ke();
}
$jumlah_harga = [];

$Template['title'] = 'Keranjang Belanja';
$Template['description'] = 'Daftar barang yang Anda masukkan ke keranjang belanja.';

ob_start();
?>
<section class="book">
  <div class="main">
    <div class="petak">
      <?php if ($barang2) : ?>
        <?php foreach ($barang2 as $buah) : ?>
          <?php
          $prep = $SQL->prepare('SELECT `nama`, `harga` FROM `barang` WHERE `ID` = ?');
          $prep->execute([$buah['barang']]);
          $barang = $prep->fetch();
          $jumlah_harga[] = $barang['harga'] * $buah['jumlah'];
          ?>
          <div class="barang-keranjang">
            <a href="/barang.php?id=<?= $buah['barang'] ?>" title="<?= htmlspecialchars($barang['nama']) ?>">
              <img src="/storage/barang/<?= htmlspecialchars($buah['barang']) ?>.webp" alt="<?= htmlspecialchars($barang['nama']) ?>">
              <h3><?= htmlspecialchars($barang['nama']) ?></h3>
            </a>
            <div class="banyak">
              <h4>Rp<?= number_format($barang['harga'] * $buah['jumlah'], 0, ',', '.') ?></h4>
              <p>/ <?= htmlspecialchars($buah['jumlah']) ?> buah</p>
            </div>
            <form action="/barang.php?id=<?= urlencode($buah['barang']) ?>" method="post">
              <button type="submit" name="hapus">
                <img src="/img/icon/bxs-cart-minus.svg" alt="Hapus dari keranjang">
              </button>
            </form>
          </div>
        <?php endforeach ?>
      <?php else : ?>
        <p class="tengah">Keranjang belanja Anda kosong.</p>
      <?php endif ?>
    </div>
  </div>
  <div class="sidebar">
    <header>
      <h1>Keranjang Belanja</h1>
    </header>
    <div class="badan">
      <?php if ($barang2) : ?>
        <h3>Rp<?= number_format(array_sum($jumlah_harga), 0, ',', '.') ?></h3>
        <button data-modal="pesan">Pesan Semua Barang</button>
      <?php else : ?>
        <p>Tambahkan barang ke keranjang belanja untuk melihatnya di sini.</p>
      <?php endif ?>
    </div>
  </div>
</section>
<?php
$Template['main'] = ob_get_clean();

ob_start();
?>
<div id="pesan" class="modal" hidden>
  <section>
    <header>
      <h2>Pesan Semua Barang</h2>
    </header>
    <form id="form-pesan" class="badan" action="" method="post">
      <p>Apakah anda yakin ingin memesan semua barang di keranjang belanja?</p>
    </form>
    <nav>
      <div class="kiri">
        <button data-aksi="tutup">Tidak</button>
      </div>
      <div class="kanan">
        <button type="submit" form="form-pesan" name="pesan">Ya</button>
      </div>
    </nav>
  </section>
</div>
<?php
$Template['foot'] = ob_get_clean();

require_once $DIR['TEMPLATE'] . 'index.php';
?>
