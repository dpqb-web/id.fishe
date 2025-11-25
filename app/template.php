<?php
function RenderTemplate(string $base = 'index'): string
{
  global $Template, $TemplateAddons;
  $TemplateDir = __DIR__ . '/template/';
  ob_start();
  if (!empty($TemplateAddons)) {
    foreach ($TemplateAddons as $x) {
      include $TemplateDir . $x . '.php';
    }
  }
  require $TemplateDir . $base . '.php';
  return ob_get_clean();
}

/* class Template
{
  public array $addons;
  private array $parts;
  private string $dir = __DIR__ . '/template/';

  public function get(string $nama) : string|array|null
  {
    return ($this->parts[$nama]) ?? null;
  }
  public function set(string $nama, string|array $isi) : void
  {
    $this->parts[$nama] = $isi;
  }
  public function block(string $nama) : void
  {
    $this->parts[$nama] = ob_get_clean();
  }
  public function render(string $base = 'public') : string
  {
    ob_start();
    if (!empty($this->addons)) {
      foreach ($this->addons as $x) {
        include $this->dir . $x . '.php';
      }
    }
    require $this->dir . $base . '.php';
    return ob_get_clean();
  }
}
$Template = new Template; */
