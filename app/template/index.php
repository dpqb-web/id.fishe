<?php
$meta = [
  'viewport' => 'width=device-width, height=device-height, initial-scale=1.0',
  'description' => ($Template['description']) ?? 'Aplikasi Fishé.',
  'author' => 'Singgih Budi Utomo, Muhammad Ivan Affandi, Muhammad Rizki Fauzan',
  'application-name' => 'Fishé',
  'theme-color' => '#e6e6e6',
  'color-scheme' => 'only light'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php foreach ($meta as $name => $content) : ?>
    <meta name="<?= htmlspecialchars($name) ?>" content="<?= htmlspecialchars($content) ?>">
  <?php endforeach ?>
  <link rel="icon" type="image/svg+xml" href="/img/icon.svg">
  <link rel="manifest" type="application/manifest+json" href="static/site.webmanifest">
  <link rel="stylesheet" media="screen" href="static/style.css">
  <title><?= $Template['title'] ?> | Fishé</title>
  <script src="static/pre.js"></script>
  <script src="static/post.js"></script>
  <?= $Template['head'] ?? '' ?>
</head>

<body>
  <main>
    <nav>
      <div class="kiri">
        <a href="/">
          <img src="/img/logo.svg" alt="Fishé" class="mono">
        </a>
        <form action="/" method="get">
          <input type="search" name="cari" placeholder="Telusuri..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" autocomplete="off">
          <button type="submit">
            <img src="/img/icon/bx-search-alt.svg" alt="Cari" class="mono">
          </button>
        </form>
      </div>
      <div class="kanan">
        <?php if (periksaMasuk()) : ?>
          <a href="/keranjang.php">
            <img src="/img/icon/bxs-cart.svg" alt="Keranjang" class="mono">
          </a>
          <a href="/pengguna.php">
            <img src="/img/icon/bxs-user.svg" alt="Pengguna" class="mono">
          </a>
        <?php else : ?>
          <a href="/auth.php?act=daftar">Daftar</a>
          <a href="/auth.php?act=masuk">Masuk</a>
        <?php endif ?>
      </div>
    </nav>
    <div class="content">
      <?= $Template['main'] ?>
    </div>
    <footer>
      <p>v0.1&alpha; &copy; <?= date('Y') ?> <?= $meta['author'] ?>.</p>
    </footer>
  </main>
  <?php if (isset($_COOKIE['pesan'])) : ?>
    <div class="pesan2">
      <?php foreach ($_COOKIE['pesan'] as $i) : ?>
        <section class="<?= $GLOBALS['JenisPesan'][$i[0]] ?>">
          <div class="icon">
            <?php switch ($i[0]):
              case 1: ?>
                <img src="/img/icon/bxs-info-octagon.svg" alt="Keterangan" class="mono">
                <?php break ?>
              <?php
              case 2: ?>
                <img src="/img/icon/bxs-alert-triangle.svg" alt="Peringatan" class="mono">
                <?php break ?>
              <?php
              case 3: ?>
                <img src="/img/icon/bxs-x-circle.svg" alt="Galat" class="mono">
                <?php break ?>
              <?php
              default: ?>
                <img src="/img/icon/bxs-check-square.svg" alt="Berhasil" class="mono">
            <?php endswitch ?>
          </div>
          <span><?= htmlspecialchars($i[1]) ?></span>
          <div class="tutup">
            <button>
              <img src="/img/icon/bx-x.svg" alt="Tutup" class="mono">
            </button>
          </div>
        </section>
      <?php endforeach ?>
    </div>
    <?php // delCook('pesan') ?>
  <?php endif ?>
  <?= $Template['foot'] ?? '' ?>
</body>

</html>
