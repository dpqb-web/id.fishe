<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../index.php';

if (isset($_POST['act'])) {
  $input = new Form($_POST);
  $input->del('act');

  if ($_POST['act'] === 'daftar') {
    if ($input->check()) {
      $valid_username = (preg_match('/^[a-zA-Z0-9-_]{3,20}$/', $input->get('username'))) ? true : false;
      $equal_password = ($input->get('password1') === $input->get('password2')) ? true : false;

      if (!$valid_username) {
        pesan(3, 'Mohon masukkan nama pengguna yang sesuai.');
      }
      if (!$equal_password) {
        pesan(3, 'Kata sandi tidak cocok.');
      }

      if ($valid_username && $equal_password) {
        $prep1 = $SQL->prepare('SELECT 1 FROM `pengguna` WHERE `username` = ? LIMIT 1');
        $prep1->execute([$input->get('username')]);

        if ($prep1->fetch()) {
          pesan(3, 'Nama pengguna sudah terdaftar. Silakan pilih nama pengguna lain.');
        } else {
          $input->san(513, 'username', 'fullname');
          $input->set('password', password_hash($input->get('password1'), PASSWORD_DEFAULT));
          $input->del('password1', 'password2');

          $params = $input->param();
          $prep2 = $SQL->prepare("INSERT INTO `pengguna` ($params[0]) VALUES ($params[1])");
          $input->bind($prep2);
          $prep2->execute();

          pesan(0, 'Pendaftaran berhasil. Silakan masuk ke akun Anda.');
          ke('auth.php?act=masuk');
        }
      }
    } else {
      pesan(3, 'Mohon isikan semua kolom yang diperlukan.');
    }
    ke('auth.php?act=daftar');
  }
  if ($_POST['act'] === 'masuk') {
    if ($input->check()) {
      $input->san(513, 'username');
      $prep = $SQL->prepare('SELECT `username`, `password` FROM `pengguna` WHERE `username` = ? LIMIT 1');
      $prep->execute([$input->get('username')]);
      $user = $prep->fetch();

      if ($user && password_verify($input->get('password'), $user['password'])) {
        $_SESSION['username'] = $user['username'];
        pesan(0, 'Anda telah berhasil masuk.');
        ke();
      } else {
        pesan(3, 'Nama pengguna atau kata sandi salah.');
      }
    } else {
      pesan(3, 'Mohon isikan semua kolom yang diperlukan.');
    }
    ke('auth.php?act=masuk');
  }
  if ($_POST['act'] === 'keluar') {
    if (periksaMasuk()) {
      session_destroy();
      pesan(1, 'Anda telah keluar dari akun Anda.');
    }
    ke();
  }
  if ($_POST['act'] === 'hapus') {
    if (periksaMasuk()) {
      $prep = $SQL->prepare('DELETE FROM `pengguna` WHERE `username` = ? LIMIT 1');
      $prep->execute([$_SESSION['username']]);
      session_destroy();
      pesan(1, 'Akun Anda telah dihapus.');
    }
    ke();
  }
}

if (periksaMasuk()) ke();

$act = $_GET['act'] ?? 'masuk';

// halaman

$Template['title'] = match ($act) {
  'daftar' => 'Daftar',
  default => 'Masuk'
};
$Template['description'] = match ($act) {
  'daftar' => '',
  default => ''
};

ob_start();
?>
<?php if ($act === 'daftar') : ?>
  <form method="post">
    <table>
      <tbody>
        <tr>
          <td><label for="username">Nama pengguna:</label></td>
          <td><input type="text" name="username" id="username"></td>
        </tr>
        <tr>
          <td><label for="fullname">Nama lengkap:</label></td>
          <td><input type="text" name="fullname" id="fullname"></td>
        </tr>
        <tr>
          <td><label for="password1">Kata sandi:</label></td>
          <td><input type="password" name="password1" id="password1"></td>
        </tr>
        <tr>
          <td><label for="password2">Konfirmasi kata sandi:</label></td>
          <td><input type="password" name="password2" id="password2"></td>
        </tr>
        <tr>
          <td></td>
          <td><button type="submit" name="act" value="daftar">Daftar</button></td>
        </tr>
      </tbody>
    </table>
  </form>
<?php else : ?>
  <form method="post">
    <table>
      <tbody>
        <tr>
          <td><label for="username">Nama pengguna:</label></td>
          <td><input type="text" name="username" id="username"></td>
        </tr>
        <tr>
          <td><label for="password1">Kata sandi:</label></td>
          <td><input type="password" name="password" id="password"></td>
        </tr>
        <tr>
          <td></td>
          <td><button type="submit" name="act" value="masuk">Masuk</button></td>
        </tr>
      </tbody>
    </table>
  </form>
<?php endif ?>
<?php
$Template['main'] = ob_get_clean();

require_once $DIR['TEMPLATE'] . 'index.php';
?>
