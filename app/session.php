<?php
session_start();

function masukDulu() : void {
  if (empty($_SESSION['user_id'])) {
    ke('login.php');
  }
}
