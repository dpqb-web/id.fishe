<?php
$TEMPLATE = [];

function render(bool $public = true) : void {
  global $TEMPLATE;
  ob_start();
  if ($public) {
    require __DIR__ . '/template/public.php';
  } else {
    require __DIR__ . '/template/private.php';
  }
  echo ob_get_clean();
}
