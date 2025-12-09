<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

if (empty($_GET['id'])) masukDulu();

if (periksaMasuk()) {
  if (isset($_POST['ikuti']) && !empty($_GET['id'])) {
    $input = new Form($_POST);
    $input->del('ikuti');
    $input->set('mengikuti', htmlspecialchars($_GET['id']));
    $input->set('diikuti', htmlspecialchars($_SESSION['username']));
    $query = ($_POST['ikuti'] === 'false') ?
      'DELETE FROM `pengikut` WHERE `mengikuti` = :mengikuti AND `diikuti` = :diikuti' :
      'INSERT INTO `pengikut` (`mengikuti`, `diikuti`) VALUES (:mengikuti, :diikuti)';
    $prep = $SQL->prepare($query);
    $input->bind($prep);
    if ($prep->execute()) {
      if ($_POST['ikuti'] === 'true') {
        pesan(0, 'Anda sekarang mengikuti ' . htmlspecialchars($_GET['id']) . '.');
      } else {
        pesan(1, 'Anda batal mengikuti ' . htmlspecialchars($_GET['id']) . '.');
      }
    } else {
      pesan(3, 'Terjadi kesalahan.');
    }
    ke('penjual.php?id=' . htmlspecialchars($_GET['id']));
  }
  if (isset($_POST['barang'])) {
    $input = new Form($_POST);
    $input->del('barang');

    $mode = $_POST['barang'];
    if ($mode === 'tambah') {
      if ($input->check()) {
        $imageAvail = (!empty($_FILES['gambar']['tmp_name'])) ? true : false;
        $validImageType = (exif_imagetype($_FILES['gambar']['tmp_name']) == IMAGETYPE_WEBP) ? true : false;
        if (!$imageAvail) {
          pesan(3, 'Mohon unggah gambar untuk barang yang ditambahkan.');
        }
        if (!$validImageType) {
          pesan(3, 'Mohon unggah gambar dengan format WEBP.');
        }

        if ($imageAvail && $validImageType) {
          $input->set('ID', random_string());
          $input->set('pemilik', htmlspecialchars($_SESSION['username']));
          $input->san(513, 'nama', 'keterangan');

          $params = $input->param();
          $prep = $SQL->prepare("INSERT INTO `barang` ($params[0]) VALUES ($params[1])");
          $input->bind($prep);
          $prep->execute();

          $fileTarget = $DIR['STORAGE'] . 'barang/' . $input->get('ID') . '.webp';
          move_uploaded_file($_FILES['gambar']['tmp_name'], $fileTarget);

          pesan(0, 'Barang berhasil ditambahkan.');
        }
      } else {
        pesan(3, 'Mohon isikan semua kolom yang diperlukan.');
      }
    }
    if ($mode == 'ubah') {
      if ($input->check()) {
        $imageAvail = (!empty($_FILES['gambar']['tmp_name'])) ? true : false;
        if ($imageAvail) {
          $validImageType = (exif_imagetype($_FILES['gambar']['tmp_name']) == IMAGETYPE_WEBP) ? true : false;
          if (!$validImageType) {
            pesan(3, 'Mohon unggah gambar dengan format WEBP.');
          }
        }

        $input->san(513, 'nama', 'keterangan');

        $params = $input->param();
        $prep = $SQL->prepare('UPDATE `barang` SET `nama` = :nama, `keterangan` = :keterangan, `harga` = :harga WHERE `ID` = :ID');
        $input->bind($prep);
        $prep->execute();

        if ($imageAvail) {
          $fileTarget = $DIR['STORAGE'] . 'barang/' . $input->get('ID') . '.webp';
          move_uploaded_file($_FILES['gambar']['tmp_name'], $fileTarget);
        }

        pesan(0, 'Barang berhasil diubah.');
      } else {
        pesan(3, 'Mohon isikan semua kolom yang diperlukan.');
      }
    }
    if ($mode == 'hapus') {
      $prep = $SQL->prepare('DELETE FROM `barang` WHERE `ID` = ?');
      $prep->execute([($input->get('ID'))]);

      $file = $DIR['STORAGE'] . 'barang/' . $input->get('ID') . '.webp';
      if (file_exists($file)) unlink($file);

      pesan(1, 'Barang berhasil dihapus.');
    }
    ke('penjual.php');
  }
}

$orangLain = (isset($_GET['id']) && $_GET['id'] !== $_SESSION['username']) ? true : false;
$curLink = '/pengguna.php' . ($orangLain ? ('?id=' . htmlspecialchars($_GET['id'])) : '');
$halaman = $_GET['hal'] ?? 'barang';

$prep = $SQL->prepare('SELECT * FROM `pengguna` WHERE `username` = ? LIMIT 1');
$prep->execute([($_GET['id'] ?? $_SESSION['username'])]);
$user = $prep->fetch();
if ($user && $user['penjual']) {
  $prep2 = $SQL->prepare('SELECT * FROM `pengikut` WHERE `mengikuti` = :username');
  $prep2->execute(['username' => $user['username']]);
  $pengikut = $prep2->fetchAll();

  $mengikuti = 0;
  $diikuti = false;
  foreach ($pengikut as $p) {
    if ($p['mengikuti'] === $user['username']) $mengikuti++;
    if ($p['diikuti'] === $_SESSION['username']) $diikuti = true;
  }
} else {
  if ($orangLain) {
    ke();
  } else {
    ke('pengguna.php');
  }
}
if ($halaman === 'barang') {
  $prep = $SQL->prepare('SELECT * FROM `barang` WHERE `pemilik` = ?');
  $prep->execute([($user['username'])]);
  $barang2 = $prep->fetchAll();
}

$Template['title'] = $user['fullname'];
$Template['description'] = '';

ob_start();
?>
<section class="book">
  <div class="main">
    <?php if ($halaman == 'tentang') : ?>
    <?php else : ?>
      <header>
        <h2>Barang Penjual</h2>
      </header>
      <?php if (!$orangLain) : ?>
        <button data-modal="tambah">Tambah Barang</button>
      <?php endif ?>
      <div class="petak">
        <?php if ($barang2) : ?>
          <?php foreach ($barang2 as $buah) : ?>
            <?php include $DIR['TEMPLATE'] . 'buah.barang.php' ?>
          <?php endforeach ?>
        <?php else : ?>
          <p class="tengah">Penjual ini belum memiliki barang.</p>
        <?php endif ?>
      </div>
    <?php endif ?>
  </div>
  <div class="sidebar">
    <header>
      <h1 title="<?= $user['fullname'] ?>"><?= $user['fullname'] ?></h1>
      <p>@<?= htmlspecialchars($user['username']) ?></p>
      <p><?= $mengikuti ?> pengikut</p>
      <?php if ($orangLain) : ?>
        <form action="" method="post">
          <?php if ($diikuti) : ?>
            <button type="submit" name="ikuti" value="false">Batal Mengikuti</button>
          <?php else : ?>
            <button type="submit" name="ikuti" value="true">Ikuti</button>
          <?php endif ?>
        </form>
      <?php endif ?>
    </header>
    <ul class="nav">
      <li>
        <a href="<?= $curLink ?>" class="aktif">Barang</a>
      </li>
    </ul>
  </div>
</section>
<?php
$Template['main'] = ob_get_clean();

ob_start();
?>
<?php if (!$orangLain) : ?>
  <div id="tambah" class="modal" hidden>
    <section>
      <header>
        <h2>Tambah Barang</h2>
      </header>
      <form id="form-tambah" class="badan" action="" method="post" enctype="multipart/form-data">
        <table>
          <tbody>
            <tr>
              <td><label for="nama">Nama Barang:</label></td>
              <td><input type="text" name="nama" id="nama"></td>
            </tr>
            <tr>
              <td><label for="keterangan">Keterangan:</label></td>
              <td><textarea name="keterangan" id="keterangan" rows="4"></textarea></td>
            </tr>
            <tr>
              <td><label for="harga">Harga (Rp):</label></td>
              <td><input type="number" name="harga" id="harga" min="0"></td>
            </tr>
            <!-- <tr>
              <td><label for="stok">Stok:</label></td>
              <td><input type="number" name="stok" id="stok" min="0"></td>
            </tr> -->
            <tr>
              <td><label for="gambar">Gambar (WEBP):</label></td>
              <td><input type="file" name="gambar" id="gambar" accept=".webp"></td>
            </tr>
          </tbody>
        </table>
      </form>
      <nav>
        <div class="kiri">
          <button data-aksi="tutup">Batal</button>
        </div>
        <div class="kanan">
          <button type="submit" form="form-tambah" name="barang" value="tambah">Tambah</button>
        </div>
      </nav>
    </section>
  </div>
  <div id="ubah" class="modal" hidden>
    <section>
      <header>
        <h2>Ubah Barang</h2>
      </header>
      <form id="form-ubah" class="badan" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ID" value="">
        <table>
          <tbody>
            <tr>
              <td><label for="nama">Nama Barang:</label></td>
              <td><input type="text" name="nama" id="nama"></td>
            </tr>
            <tr>
              <td><label for="keterangan">Keterangan:</label></td>
              <td><textarea name="keterangan" id="keterangan" rows="4"></textarea></td>
            </tr>
            <tr>
              <td><label for="harga">Harga (Rp):</label></td>
              <td><input type="number" name="harga" id="harga" min="0"></td>
            </tr>
            <!-- <tr>
              <td><label for="stok">Stok:</label></td>
              <td><input type="number" name="stok" id="stok" min="0"></td>
            </tr> -->
            <tr>
              <td><label for="gambar">Gambar (WEBP):</label></td>
              <td><input type="file" name="gambar" id="gambar" accept=".webp"></td>
            </tr>
          </tbody>
        </table>
      </form>
      <nav>
        <div class="kiri">
          <button data-aksi="tutup">Batal</button>
        </div>
        <div class="kanan">
          <button type="submit" form="form-ubah" name="barang" value="ubah">Ubah</button>
        </div>
      </nav>
    </section>
  </div>
  <div id="hapus" class="modal" hidden="hidden">
    <section>
      <header>
        <h2>Hapus Barang</h2>
      </header>
      <form id="form-hapus" class="badan" action="" method="post">
        <input type="hidden" name="ID" value="">
        <p>Apakah anda yakin ingin menghapus barang "<span class="nama"></span>"?</p>
      </form>
      <nav>
        <div class="kiri">
          <button type="submit" form="form-hapus" name="barang" value="hapus">Hapus</button>
        </div>
        <div class="kanan">
          <button data-aksi="tutup">Batal</button>
        </div>
      </nav>
    </section>
  </div>
  <pre id="data-barang" hidden><?= htmlspecialchars(json_encode($barang2)) ?></pre>
  <script>
    var DOMDataBarang = document.getElementById('data-barang');
    var dataBarang = JSON.parse(DOMDataBarang.textContent);
    DOMDataBarang.remove();
    DOMDataBarang = undefined;

    document.querySelectorAll('button[data-modal="ubah"]').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        var idBarang = this.closest('.barang').getAttribute('data-id');
        var barang = dataBarang.find(function(b) {
          return b.ID === idBarang;
        });

        var modal = document.querySelector('#ubah.modal');
        modal.querySelector('input[name="ID"]').value = barang.ID;
        modal.querySelector('input[name="nama"]').value = barang.nama;
        modal.querySelector('textarea[name="keterangan"]').value = barang.keterangan;
        modal.querySelector('input[name="harga"]').value = barang.harga;
        // modal.querySelector('input[name="stok"]').value = barang.stok;
      });
    });

    document.querySelectorAll('button[data-modal="hapus"]').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        var idBarang = this.closest('.barang').getAttribute('data-id');
        var barang = dataBarang.find(function(b) {
          return b.ID === idBarang;
        });

        var modal = document.querySelector('#hapus.modal');
        modal.querySelector('input[name="ID"]').value = barang.ID;
        modal.querySelector('span.nama').textContent = barang.nama;
      });
    });
  </script>
<?php endif ?>
<?php
$Template['foot'] = ob_get_clean();

require_once $DIR['TEMPLATE'] . 'index.php';
?>
