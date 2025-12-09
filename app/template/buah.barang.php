<?php
// required variables:
// $buah

// optional variables:
// $orangLain
?>
<div class="barang" data-id="<?= $buah['ID'] ?>">
  <a href="/barang.php?id=<?= $buah['ID'] ?>">
    <img src="/storage/barang/<?= $buah['ID'] ?>.webp" alt="<?= htmlspecialchars($buah['nama']) ?>" title="<?= htmlspecialchars($buah['nama']) ?>">
    <div class="info">
      <h3 title="<?= htmlspecialchars($buah['nama']) ?>"><?= htmlspecialchars($buah['nama']) ?></h3>
      <p class="harga">Rp<?= number_format($buah['harga'], 0, ',', '.') ?></p>
    </div>
  </a>
  <?php if (isset($orangLain) && !$orangLain) : ?>
    <button data-modal="ubah">
      <img src="/img/icon/bxs-edit-alt.svg" alt="Ubah">
    </button>
    <button data-modal="hapus">
      <img src="/img/icon/bxs-trash.svg" alt="Hapus">
    </button>
  <?php endif ?>
</div>
