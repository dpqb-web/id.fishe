<?php
function ke(string $tujuan = '') : void {
  header('Location: /' . $tujuan);
  exit();
}

foreach (['cookies', 'session', 'sql', 'template'] as $x) {
  require_once __DIR__ . '/' . $x . '.php';
}
