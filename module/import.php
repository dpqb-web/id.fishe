<?php
function AddLayout(string $name = 'index') : string {
  return $DIR['TEMPLATE'] . $name . '.php';
}
