<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

masukDulu();

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
  ke('pengguna.php');
}

$prep = $SQL->prepare('SELECT * FROM `pengguna` WHERE `username` = ? LIMIT 1');
$prep->execute([($_SESSION['username'])]);
$user = $prep->fetch();

$Template['title'] = ($user['fullname']) ?? 'Tidak Ditemukan';
$Template['description'] = '';

ob_start();
?>
<section class="box">
  <?php if ($user['penjual']) : ?>
    <a href="/penjual.php">kelola barang</a>
    <button data-modal="penjual">batal menjadi penjual</button>
  <?php else : ?>
    <button data-modal="penjual">jadi penjual</button>
  <?php endif ?>
  <button data-modal="hapus">hapus akun</button>
  <button type="submit" form="keluar">Keluar</button>
</section>
<?php
$Template['main'] = ob_get_clean();

ob_start();
?>
<form id="keluar" action="/auth.php" method="post" hidden>
  <input type="hidden" name="act" value="keluar">
</form>
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
    <form id="form-hapus" class="badan" action="/auth.php" method="post">
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
<?php
$Template['foot'] = ob_get_clean();

require_once $DIR['TEMPLATE'] . 'index.php';
?>
