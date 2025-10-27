<?php
function render(bool $public = true, string ...$addon) : string {
  global $TEMPLATE;
  $locTMPL = __DIR__ . '/template/';

  ob_start();
  if ($addon) {
    foreach ($addon as $x) {
      require $locTMPL . $x . '.php';
    }
  }
  if ($public) {
    require $locTMPL . 'public.php';
  } else {
    require $locTMPL . 'private.php';
  }
  return ob_get_clean();
}
