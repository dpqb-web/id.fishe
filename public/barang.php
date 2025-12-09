<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

if (!isset($_GET['id'])) ke();

$prep = $SQL->prepare('SELECT * FROM `barang` WHERE `id` = ?');
$prep->execute([$_GET['id']]);
$buah = $prep->fetch();
if (!$buah) ke();

if (periksaMasuk()) {
  if (isset($_POST['keranjang'])) {
    $input = new Form($_POST, 'keranjang');

    if ($_POST['keranjang'] === 'tambah') {
      $input->set('pembeli', $_SESSION['username']);
      $input->set('barang', $buah['ID']);
      $input->san(FILTER_VALIDATE_INT, 'jumlah');

      if ($input->check()) {
        $params = $input->param();
        $prep = $SQL->prepare("INSERT INTO `keranjang` ($params[0]) VALUES ($params[1])");
        $input->bind($prep);
        $prep->execute();

        pesan(0, 'Berhasil menambahkan barang ke keranjang.');
      }
    }
    if ($_POST['keranjang'] === 'hapus') {
      $prep = $SQL->prepare('DELETE FROM `keranjang` WHERE `pembeli` = ? AND `barang` = ?');
      $prep->execute([$_SESSION['username'], $buah['ID']]);

      pesan(1, 'Berhasil menghapus barang dari keranjang.');
    }

    ke('barang.php?id=' . urlencode($buah['ID']));
  }

  if (isset($_POST['ulas'])) {
    $input = new Form($_POST, 'ulas');
    $input->del('ulas');

    if ($_POST['ulas'] === 'tambah') {
      $input->set('pembeli', $_SESSION['username']);
      $input->set('barang', $buah['ID']);
      $input->san(FILTER_VALIDATE_INT, 'bintang');

      if ($input->check()) {
        $params = $input->param();
        $prep = $SQL->prepare("INSERT INTO `ulasan` ($params[0]) VALUES ($params[1])");
        $input->bind($prep);
        $prep->execute();

        pesan(0, 'Terima kasih telah memberikan ulasan.');
      }
    }
    if ($_POST['ulas'] === 'hapus') {
      $prep = $SQL->prepare('DELETE FROM `ulasan` WHERE `pembeli` = ? AND `barang` = ?');
      $prep->execute([$_SESSION['username'], $buah['ID']]);

      pesan(1, 'Berhasil menghapus ulasan.');
    }

    ke('barang.php?id=' . urlencode($buah['ID']));
  }
}

$prep2 = $SQL->prepare('SELECT * FROM `ulasan` WHERE `barang` = ?');
$prep2->execute([$buah['ID']]);
$ulasan2 = $prep2->fetchAll();
$rata2_ulasan = 0;
if ($ulasan2) {
  $total_bintang = 0;
  foreach ($ulasan2 as $ulasan) {
    $total_bintang += $ulasan['bintang'];
  }
  $rata2_ulasan = $total_bintang / count($ulasan2);
}

$prep3 = $SQL->prepare('SELECT 1 FROM `keranjang` WHERE `pembeli` = ? AND `barang` = ?');
$prep3->execute([$_SESSION['username'] ?? '', $buah['ID']]);
$sudah_ada = (bool) $prep3->fetch();

$prep4 = $SQL->prepare('SELECT 1 FROM `pesanan` WHERE `pembeli` = ? AND `barang` = ?');
$prep4->execute([$_SESSION['username'] ?? '', $buah['ID']]);
$sudah_beli = (bool) $prep4->fetch();

$prep5 = $SQL->prepare('SELECT 1 FROM `ulasan` WHERE `pembeli` = ? AND `barang` = ?');
$prep5->execute([$_SESSION['username'] ?? '', $buah['ID']]);
$sudah_diulas = (bool) $prep5->fetch();

$orangLain = ($buah['pemilik'] === ($_SESSION['username'] ?? '')) ? false : true;

$Template['title'] = $buah['nama'];
$Template['description'] = $buah['keterangan'];

ob_start();
?>
<section class="box ket-barang">
  <a href="/storage/barang/<?= htmlspecialchars($buah['ID']) ?>.webp" target="_blank" rel="noopener noreferrer">
    <img src="/storage/barang/<?= htmlspecialchars($buah['ID']) ?>.webp" alt="<?= htmlspecialchars($buah['nama']) ?>">
  </a>
  <div class="kanan">
    <div class="rincian">
      <h1 title="<?= htmlspecialchars($buah['nama']) ?>"><?= htmlspecialchars($buah['nama']) ?></h1>
      <p><?= nl2br(htmlspecialchars($buah['keterangan'])) ?></p>
    </div>
    <div class="beli">
      <?php if (periksaMasuk() && $orangLain) : ?>
        <?php if ($sudah_ada) : ?>
          <form action="" method="post" class="kiri">
            <button type="submit" name="keranjang" value="hapus">
              <img src="/img/icon/bxs-cart-minus.svg" alt="Hapus dari keranjang">
            </button>
          </form>
        <?php else : ?>
          <form class="kiri" action="" method="post">
            <input type="number" name="jumlah" id="jumlah" value="1" min="1">
            <button type="submit" name="keranjang" value="tambah">
              <img src="/img/icon/bxs-cart-plus.svg" alt="Tambah ke keranjang">
            </button>
          </form>
        <?php endif ?>
      <?php endif ?>
      <div class="kanan">
        <h4>Rp<span id="harga"><?= htmlspecialchars(number_format($buah['harga'], 0, ',', '.')) ?></span></h4>
      </div>
    </div>
  </div>
</section>
<section class="book">
  <div class="main">
    <?php if ($sudah_beli) : ?>
      <?php if ($sudah_diulas) : ?>
        <form action="" method="post">
          <button type="submit" name="ulas" value="hapus">Hapus Ulasan Saya</button>
        </form>
      <?php else : ?>
        <form action="" method="post">
          <div class="bintang">
            <input type="radio" id="bintang5" name="bintang" value="5">
            <label for="bintang5">
              <img src="/img/icon/bxs-star.svg" alt="bintang 5 dipilih">
              <img src="/img/icon/bx-star.svg" alt="bintang 5">
            </label>
            <input type="radio" id="bintang4" name="bintang" value="4">
            <label for="bintang4">
              <img src="/img/icon/bxs-star.svg" alt="bintang 4 dipilih">
              <img src="/img/icon/bx-star.svg" alt="bintang 4">
            </label>
            <input type="radio" id="bintang3" name="bintang" value="3" checked>
            <label for="bintang3">
              <img src="/img/icon/bxs-star.svg" alt="bintang 3 dipilih">
              <img src="/img/icon/bx-star.svg" alt="bintang 3">
            </label>
            <input type="radio" id="bintang2" name="bintang" value="2">
            <label for="bintang2">
              <img src="/img/icon/bxs-star.svg" alt="bintang 2 dipilih">
              <img src="/img/icon/bx-star.svg" alt="bintang 2">
            </label>
            <input type="radio" id="bintang1" name="bintang" value="1">
            <label for="bintang1">
              <img src="/img/icon/bxs-star.svg" alt="bintang 1 dipilih">
              <img src="/img/icon/bx-star.svg" alt="bintang 1">
            </label>
          </div>
          <textarea name="komentar" id="komentar"></textarea>
          <button type="submit" name="ulas" value="tambah">Beri Ulasan</button>
        </form>
      <?php endif ?>
    <?php endif ?>
    <?php if ($ulasan2) : ?>
      <?php foreach ($ulasan2 as $ulasan) : ?>
        <div class="ulasan">
          <div class="header">
            <h4><?= htmlspecialchars($ulasan['pembeli']) ?></h4>
            <div class="bintang">
              <?php for ($i = 0; $i < 5; $i++) : ?>
                <?php if ($i < $ulasan['bintang']) : ?>
                  <img src="/img/icon/bxs-star.svg" alt="bintang <?= $i + 1 ?> dipilih">
                <?php else : ?>
                  <img src="/img/icon/bx-star.svg" alt="bintang <?= $i + 1 ?>">
                <?php endif ?>
              <?php endfor ?>
            </div>
          </div>
          <p><?= nl2br(htmlspecialchars($ulasan['komentar'])) ?></p>
        </div>
      <?php endforeach ?>
    <?php else : ?>
      <p>Belum ada ulasan untuk barang ini.</p>
    <?php endif ?>
  </div>
  <div class="sidebar">
    <header>
      <img src="/img/icon/bxs-star.svg" alt="bintang">
      <h1><?= number_format($rata2_ulasan, 1) ?></h1>
      <p>dari <?= count($ulasan2) ?> ulasan</p>
    </header>
  </div>
</section>
<?php
$Template['main'] = ob_get_clean();

ob_start();
?>
<script>
  var harga = document.getElementById('harga');
  var hargaAwal = parseInt(harga.textContent.replace(/\./g, '')) || 0;
  var jumlah = document.getElementById('jumlah');
  if (jumlah) {
    jumlah.addEventListener('input', function() {
      var jumlahInt = parseInt(jumlah.value) || 0;
      harga.textContent = (jumlahInt * hargaAwal).toLocaleString('id-ID');
    });
  }
</script>
<?php
$Template['foot'] = ob_get_clean();

require_once $DIR['TEMPLATE'] . 'index.php';
?>
