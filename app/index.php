<?php
function ke(string $tujuan = '/') : void {
  header("Location: $tujuan");
  exit();
}

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/sql.php';
require_once __DIR__ . '/template.php';
