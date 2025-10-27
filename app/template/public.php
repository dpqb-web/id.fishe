<?php
$meta = [
  'viewport' => 'width=device-width, height=device-height, initial-scale=1.0',
  'description' => ($TEMPLATE['description']) ?? 'Aplikasi Fishe.',
  'author' => 'Singgih Budi Utomo, Muhammad Ivan Affandi, Muhammad Rizki Fauzan',
  'application-name' => 'Fishe',
  'theme-color' => '#ffffff',
  'color-scheme' => 'light dark',
  'apple-mobile-web-app-capable' => 'yes',
  'apple-mobile-web-app-status-bar-style' => 'black-translucent'
];

$menuItem = [
  ['name' => 'Beranda', 'link' => '/', 'img' => 'home'],
  ['name' => 'Tentang', 'link' => '/about.php', 'img' => 'about'],
  ['name' => 'Kontak', 'link' => '/contact.php', 'img' => 'contact']
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php foreach ($meta as $name => $content): ?>
    <meta name="<?= htmlspecialchars($name) ?>" content="<?= htmlspecialchars($content) ?>">
  <?php endforeach; ?>
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="manifest" type="application/manifest+json" href="/site.webmanifest">
  <link rel="stylesheet" media="screen" href="/style.css">
  <title><?= $TEMPLATE['title'] ?> | Fishe</title>
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
          console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
          console.error('ServiceWorker registration failed: ', err);
        });
      });
    } else {
      console.warn('ServiceWorker is not supported in this browser. Progressive Web App features may not be available.');
    }

    function cancel(event) {
      event.preventDefault();
      event.stopPropagation();
      return false;
    }
  </script>
  <?= ($TEMPLATE['head']) ?? '' ?>
</head>

<body>
  <div class="body">
    <nav>
      <ul class="mainMenu">
        <?php foreach ($menuItem as $item): ?>
          <li>
            <a href="<?= $item['link'] ?>">
              <picture>
                <source media="(prefers-color-scheme: dark)" srcset="/img/dark/bx-<?= $item['img'] ?>.svg">
                <img src="/img/bx-<?= $item['img'] ?>.svg" alt="Ikon <?= $item['name'] ?>">
              </picture>
              <?= $item['name'] ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <?php if (isset($_COOKIE['pesan'])) : ?>
      <div class="pesan2">
        <?php foreach ($JenisPesan as $urutan => $jenis) : ?>
          <?php foreach ($_COOKIE[$urutan] as $isi) : ?>
            <div class="pesan <?= $jenis ?>">
              <?= $isi ?>
            </div>
          <?php endforeach ?>
        <?php endforeach ?>
      </div>
      <?php delCook('pesan') ?>
    <?php endif ?>
    <main>
      <?= $TEMPLATE['main'] ?>
    </main>
    <footer class="only desktop">
      &copy; <?= date('Y') ?> <?= $meta['author'] ?>. Semua hak dilindungi undang-undang.
    </footer>
  </div>
  <?= ($TEMPLATE['foot']) ?? '' ?>
</body>

</html>
