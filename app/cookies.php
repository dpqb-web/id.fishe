<?php
function delCook(string ...$nama2): void
{
  foreach ($nama2 as $nama) {
    if (isset($_COOKIE[$nama])) {
      setcookie($nama, '', 1);
    }
  }
}

$JenisPesan = ['berhasil', 'keterangan', 'peringatan', 'galat'];
function buatPesan(int $jenis, string $isi) : bool {
  return setcookie("pesan[$jenis][]", $isi, time()+1);
}
