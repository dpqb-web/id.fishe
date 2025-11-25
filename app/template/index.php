<?php
$meta = [
  'viewport' => 'width=device-width, height=device-height, initial-scale=1.0',
  'description' => ($Template['description']) ?? 'Aplikasi Fishé.',
  'author' => 'Singgih Budi Utomo, Muhammad Ivan Affandi, Muhammad Rizki Fauzan',
  'application-name' => 'Fishé',
  'theme-color' => '#ffffff',
  'color-scheme' => 'light dark',
  'apple-mobile-web-app-capable' => 'yes',
  'apple-mobile-web-app-status-bar-style' => 'black-translucent'
];
$icon = [16, 24, 32, 48, 180, 256, 512, 1024];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php foreach ($meta as $name => $content) : ?>
    <meta name="<?= htmlspecialchars($name) ?>" content="<?= htmlspecialchars($content) ?>">
  <?php endforeach ?>
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <?php foreach ($icon as $size) : ?>
    <link rel="icon" type="image/png" sizes="<?= $size ?>x<?= $size ?>" href="/assets/img/icon-<?= $size ?>.png">
  <?php endforeach ?>
  <link rel="icon" type="image/svg+xml" href="/assets/img/icon.svg">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icon-180.png">
  <link rel="manifest" type="application/manifest+json" href="/assets/site.webmanifest">
  <link rel="stylesheet" media="screen" href="/assets/style.css">
  <link rel="stylesheet" media="screen and (max-width: 480px)" href="/assets/mobile.css">
  <!-- <link rel="stylesheet" media="screen" href="/assets/dark.css"> -->
  <title><?= $Template['title'] ?> | Fishé</title>
  <script src="/assets/pre.js"></script>
  <script src="/assets/post.js"></script>
  <?= $Template['head'] ?? '' ?>
</head>

<body>
  <main>
    <nav class="desktop">
      <div class="kiri">
        <a href="/">
          <img src="/assets/img/logo.svg" alt="Fishé" class="mono">
        </a>
        <form action="/" method="get">
          <input type="search" name="cari" placeholder="Cari barang..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" autocomplete="off">
          <button type="submit">
            <img src="/assets/img/bx/bx-search-alt.svg" alt="Cari" class="mono">
          </button>
        </form>
      </div>
      <div class="kanan">
        <?php if (periksaMasuk()) : ?>
          <a href="/cart.php">
            <img src="/assets/img/bx/bxs-cart.svg" alt="Keranjang" class="mono">
          </a>
          <a href="/user.php">
            <img src="/assets/img/bx/bxs-user.svg" alt="Pengguna" class="mono">
          </a>
        <?php else : ?>
          <a href="/auth.php?act=daftar">Daftar</a>
          <a href="/auth.php?act=masuk">Masuk</a>
        <?php endif ?>
      </div>
    </nav>
    <header>
      <?= $Template['header'] ?? '' ?>
    </header>
    <div class="content">
      <?= $Template['main'] ?>
    </div>
    <nav class="mobile">
      <ul>
        <li>
          <a href="/">
            <img src="/assets/img/bx/bxs-home.svg" alt="Beranda" class="mono">
          </a>
        </li>
        <li>
          <a href="/?cari">
            <img src="/assets/img/bx/bx-search-alt.svg" alt="Cari" class="mono">
          </a>
        </li>
        <?php if (periksaMasuk()) : ?>
          <li>
            <a href="/cart.php">
              <img src="/assets/img/bx/bxs-cart.svg" alt="Keranjang" class="mono">
            </a>
          </li>
          <li>
            <a href="/user/transaksi.php">
              <img src="/assets/img/bx/bxs-receipt.svg" alt="Transaksi" class="mono">
            </a>
          </li>
        <?php endif ?>
        <li>
          <a href="/user.php">
            <img src="/assets/img/bx/bxs-user.svg" alt="Pengguna" class="mono">
          </a>
        </li>
      </ul>
    </nav>
    <footer>
      &copy; <?= date('Y') ?> <?= $meta['author'] ?>.
    </footer>
  </main>
  <?php if (isset($_COOKIE['pesan'])) : ?>
    <div class="pesan2">
      <?php foreach ($_COOKIE['pesan'] as $i) : ?>
        <section class="<?= $GLOBALS['JenisPesan'][$i[0]] ?>">
          <div class="icon">
            <?php switch ($i[0]):
              case 1: ?>
                <img src="/assets/img/bx/bxs-info-octagon.svg" alt="Keterangan" class="mono">
                <?php break ?>
              <?php
              case 2: ?>
                <img src="/assets/img/bx/bxs-alert-triangle.svg" alt="Peringatan" class="mono">
                <?php break ?>
              <?php
              case 3: ?>
                <img src="/assets/img/bx/bxs-x-circle.svg" alt="Galat" class="mono">
                <?php break ?>
              <?php
              default: ?>
                <img src="/assets/img/bx/bxs-check-square.svg" alt="Berhasil" class="mono">
            <?php endswitch ?>
          </div>
          <span><?= htmlspecialchars($i[1]) ?></span>
          <div class="tutup">
            <button>
              <img src="/assets/img/bx/bx-x.svg" alt="Tutup" class="mono">
            </button>
          </div>
        </section>
      <?php endforeach ?>
    </div>
    <?php delCook('pesan') ?>
  <?php endif ?>
  <?= $Template['foot'] ?? '' ?>
</body>

</html>
