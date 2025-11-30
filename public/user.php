<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

if (empty($_GET['id'])) masukDulu();

if (periksaMasuk()) {
  if (!empty($_POST['penjual'])) {
    $input = new Form($_POST);
    $input->set('username', htmlspecialchars($_SESSION['username']));
    $input->san(FILTER_VALIDATE_BOOL, 'penjual');
    $prep = $SQL->prepare('UPDATE `pengguna` SET `penjual` = :penjual WHERE `username` = :username');
    $input->bind($prep);
    $prep->execute();
    if ($input->get('penjual')) {
      pesan(0, 'Anda telah menjadi penjual.');
    } else {
      pesan(1, 'Anda telah keluar dari menjadi penjual.');
    }
    ke('user.php');
  }
  if (isset($_POST['ikuti']) && !empty($_GET['id'])) {
    $input = new Form($_POST);
    $input->del('ikuti');
    $input->set('mengikuti', htmlspecialchars($_GET['id']));
    $input->set('diikuti', htmlspecialchars($_SESSION['username']));
    $prep = $SQL->prepare('INSERT INTO `pengikut` (`mengikuti`, `diikuti`) VALUES (:mengikuti, :diikuti)');
    $input->bind($prep);
    if ($prep->execute()) {
      pesan(0, 'Anda sekarang mengikuti ' . htmlspecialchars($_GET['id']) . '.');
    } else {
      pesan(3, 'Gagal mengikuti pengguna tersebut.');
    }
    ke('user.php?id=' . htmlspecialchars($_GET['id']));
  }
}

$orangLain = (isset($_GET['id']) && $_GET['id'] !== $_SESSION['username']) ? true : false;

$prep = $SQL->prepare('SELECT * FROM `pengguna` WHERE `username` = ? LIMIT 1');
$prep->execute([(($_GET['id']) ?? $_SESSION['username'])]);
$user = $prep->fetch();
if ($user) {
  $prep2 = $SQL->prepare('SELECT * FROM `pengikut` WHERE `mengikuti` = :username OR `diikuti` = :username');
  $prep2->execute(['username' => $user['username']]);
  $pengikut = $prep2->fetchAll();

  $mengikuti = 0;
  $diikuti = 0;
  foreach ($pengikut as $p) {
    if ($p['diikuti'] === $user['username']) $mengikuti++;
    if ($p['mengikuti'] === $user['username']) $diikuti++;
  }
}

$Template['title'] = ($user['fullname']) ?? 'Tidak Ditemukan';
$Template['description'] = '';

ob_start();
?>
<?php if ($user) : ?>
  <h1><?= $user['fullname'] ?></h1>
  <p><?= $mengikuti ?> pengikut</p>
  <p><?= $diikuti ?> yang diikuti</p>
<?php else : ?>
  <h1>Pengguna Tidak Ditemukan</h1>
<?php endif ?>
<?php
$Template['header'] = ob_get_clean();

ob_start();
?>
<?php if ($orangLain) : ?>
  <section class="box">
    <!-- <a href="/auth.php?act=keluar">Keluar</a> -->
  </section>
<?php else : ?>
  <section class="box">
    <?php if ($user['penjual']) : ?>
      <a href="#penjual" data-modal="penjual">batal menjadi penjual</a>
    <?php else : ?>
      <a href="#penjual" data-modal="penjual">jadi penjual</a>
    <?php endif ?>
    <a href="#hapus" data-modal="hapus">hapus akun</a>
    <a href="/auth.php?act=keluar">Keluar</a>
  </section>
<?php endif ?>
<?php
$Template['main'] = ob_get_clean();

ob_start();
?>
<?php if ($orangLain) : ?>
  <div id="jadi-penjual" class="dialog-modal" hidden>
    <section>
      <header></header>
      <div class="badan"></div>
      <nav></nav>
    </section>
  </div>
<?php else : ?>
  <div id="penjual" class="modal" hidden>
    <section>
      <?php if ($user['penjual']) : ?>
        <header>
          <h2>Batal menjadi penjual</h2>
        </header>
        <form id="form-penjual" class="badan" action="" method="post">
          <input type="hidden" name="penjual" value="false">
          <p>Apakah anda yakin ingin mengundurkan diri menjadi penjual?</p>
          <p>Semua barang anda akan dihapus dan tidak dapat dikembalikan!</p>
        </form>
        <nav>
          <div class="kiri">
            <button type="submit" form="form-penjual">Ya</button>
          </div>
          <div class="kanan">
            <button data-aksi="tutup">Tidak</button>
          </div>
        </nav>
      <?php else : ?>
        <header>
          <h2>Menjadi penjual</h2>
        </header>
        <form id="form-penjual" class="badan" action="" method="post">
          <input type="hidden" name="penjual" value="true">
          <p>Apakah anda yakin ingin menjadi penjual?</p>
        </form>
        <nav>
          <div class="kiri">
            <button data-aksi="tutup">Tidak</button>
          </div>
          <div class="kanan">
            <button type="submit" form="form-penjual">Ya</button>
          </div>
        </nav>
      <?php endif ?>
    </section>
  </div>
  <div id="hapus" class="modal" hidden="hidden">
    <section>
      <header>
        <h2>Hapus akun</h2>
      </header>
      <form id="form-hapus" class="badan" action="/auth.php" method="get">
        <input type="hidden" name="act" value="hapus">
        <p>Apakah anda yakin ingin menghapus akun anda?</p>
        <p>Semua data anda akan dihapus dan tidak dapat dikembalikan!</p>
      </form>
      <nav>
        <div class="kiri">
          <button type="submit" form="form-hapus">Ya</button>
        </div>
        <div class="kanan">
          <button data-aksi="tutup">Tidak</button>
        </div>
      </nav>
    </section>
  </div>
<?php endif ?>
<?php
$Template['foot'] = ob_get_clean();

echo RenderTemplate();
?>
