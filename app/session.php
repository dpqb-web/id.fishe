<?php
session_start();

function periksaMasuk() : bool {
  if (empty($_SESSION['username']) || empty($_SESSION['fullname'])) {
    return false;
  }
  return true;
}

function masukDulu() : void {
  if (periksaMasuk()) {
    ke('login.php');
  }
}
