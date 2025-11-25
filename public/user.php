<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

if (!empty($_POST['penjual']) && periksaMasuk()) {
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

$showUser = (!empty($_GET['id']) || periksaMasuk()) ? true : false;

if ($showUser) {
  $prep = $SQL->prepare('SELECT * FROM `pengguna` WHERE `username` = ? LIMIT 1');
  $sql_param = ($_GET['id']) ?? $_SESSION['username'];
  $prep->execute([$sql_param]);
  $user = $prep->fetch();

  $Template['title'] = $user['fullname'];
  $Template['description'] = '';
} else {
  $Template['title'] = 'Pengguna';
  $Template['description'] = '';
}

ob_start();
?>
<?php if (!empty($_GET['id'])) : ?>
  <h1 class="tengah"><?= $user['username'] ?></h1>
<?php elseif (periksaMasuk()) : ?>
  <h1 class="tengah"><?= $user['username'] ?></h1>
<?php else : ?>
  <h1 class="tengah">Pengguna</h1>
<?php endif ?>
<?php
$Template['header'] = ob_get_clean();

ob_start();
?>
<?php if (!empty($_GET['id'])) : ?>
  <section class="box">
    <!-- <a href="/auth.php?act=keluar">Keluar</a> -->
  </section>
<?php elseif (periksaMasuk()) : ?>
  <section class="box">
    <?php if ($user['penjual']) : ?>
      <a href="#penjual">batal menjadi penjual</a>
    <?php else : ?>
      <a href="#penjual">jadi penjual</a>
    <?php endif ?>
    <a href="/auth.php?act=keluar">Keluar</a>
  </section>
<?php else : ?>
  <section class="box">
    <div class="margin">
      <span>Silakan <a href="/auth.php?act=masuk">masuk</a> atau <a href="/auth.php?act=daftar">daftar</a> untuk melihat profil pengguna.</span>
    </div>
  </section>
<?php endif ?>
<?php
$Template['main'] = ob_get_clean();

ob_start();
?>
<?php if (!empty($_GET['id'])) : ?>
  <div id="jadi-penjual" class="dialog-modal" hidden>
    <section>
      <header></header>
      <div class="badan"></div>
      <nav></nav>
    </section>
  </div>
<?php elseif (periksaMasuk()) : ?>
  <div id="penjual" class="modal" hidden>
    <section>
      <?php if ($user['penjual']) : ?>
        <header>
          <h2>Menjadi penjual</h2>
        </header>
        <form id="form-penjual" class="badan" action="" method="post">
          <input type="hidden" name="penjual" value="false">
          <p>Apakah anda yakin ingin mengundurkan diri menjadi penjual?</p>
          <p>Semua barang anda akan dihapus dan tidak dapat dikembalikan!</p>
        </form>
        <nav>
          <div class="kiri">
            <button data-aksi="tutup">Tidak</button>
          </div>
          <div class="kanan">
            <button type="submit" form="form-penjual">Ya</button>
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
  <script>
    document.querySelector('a[href="#penjual"]').addEventListener('click', function(e) {
      cancel(e);
      document.getElementById('penjual').hidden = false;
    });
  </script>
<?php else : ?>
  <!--  -->
<?php endif ?>
<?php
$Template['foot'] = ob_get_clean();

echo RenderTemplate();
?>
