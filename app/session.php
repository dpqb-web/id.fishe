<?php
session_start();

function periksaMasuk() : bool {
  return (empty($_SESSION['username'])) ? false : true;
}

function masukDulu() : void {
  if (periksaMasuk()) ke('auth.php');
}
