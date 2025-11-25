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
function pesan(int $jenis, string $isi): void
{
  static $jumlah = 0;
  setcookie("pesan[$jumlah][0]", $jenis, time() + 1);
  setcookie("pesan[$jumlah][1]", $isi, time() + 1);
  $jumlah++;
}

/* class Pesan
{
  public const array jenis = ['berhasil', 'keterangan', 'peringatan', 'galat'];
  private int $jumlah = 0;

  public function set(int $jenis, string $isi) : void
  {
    setcookie("pesan[$this->jumlah][0]", $jenis, time() + 1);
    setcookie("pesan[$this->jumlah][1]", $isi, time() + 1);
    $this->jumlah++;
  }
  public function delAll() : void
  {
    delCook('pesan');
  }
}
$Pesan = new Pesan; */
