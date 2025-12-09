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

class Notification
{
  public const string keyName = 'Notification';
  public const array type = ['success', 'information', 'warning', 'error'];
  private int $count = 0;

  public function add(int $type, string $content) : void
  {
    setcookie(self::keyName ."[$this->count][0]", $type, time() + 1);
    setcookie(self::keyName ."[$this->count][1]", $content, time() + 1);
    $this->count++;
  }
  public function delAll() : void
  {
    delCook('pesan');
  }
}
