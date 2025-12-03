<?php
class NotMD
{
  public function decode(string $text) : string
  {
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $text = trim($text, "\n");
    return $text;
  }
}
