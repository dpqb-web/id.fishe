<?php
function ke(string $tujuan = '') : void {
  header('Location: /' . $tujuan);
  exit();
}

foreach (['cookies', 'form', 'session', 'sql', 'random'] as $x) {
  require_once __DIR__ . '/' . $x . '.php';
}
