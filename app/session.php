<?php
session_start();

function periksaMasuk() : bool {
  return !empty($_SESSION['username']);
}

function masukDulu() : void {
  if (!periksaMasuk()) ke('auth.php');
}

class Authorization
{
  public static function check() : bool
  {
    $keys = [$_SESSION['username']];

    foreach ($keys as $key) {
      if (empty($key)) {
        return false;
      }
    }
    return true;
  }
  public static function required() : void
  {
    if (!self::check()) {
      # code...
    }
  }
  public static function denied() : void
  {
    if (self::check()) {
      # code...
    }
  }
}
