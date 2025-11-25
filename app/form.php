<?php
class Form
{
  private array $input;

  function __construct(array $input)
  {
    $this->input = $input;
  }
  public function get(string $kunci = '') : mixed
  {
    return empty($kunci) ? $this->input : ($this->input[$kunci] ?? null);
  }
  public function set(string $var, mixed $value) : void
  {
    $this->input[$var] = $value;
  }
  public function del(string ...$nama) : void
  {
    foreach ($nama as $n) {
      if (isset($this->input[$n])) unset($this->input[$n]);
    }
  }
  public function ren(array $nama2) : void
  {
    foreach ($nama2 as $lama => $baru) {
      if (isset($this->input[$lama])) {
        $this->input[$baru] = $this->input[$lama];
        unset($this->input[$lama]);
      }
    }
  }
  public function san(int $type, string ...$kunci) : void
  {
    foreach ($kunci as $k) {
      if (isset($this->input[$k])) {
        // FILTER_SANITIZE_STRING || FILTER_SANITIZE_STRIPPED
        if ($type == 513) {
          $this->input[$k] = htmlspecialchars($this->input[$k]);
        } else {
          $this->input[$k] = filter_var($this->input[$k], $type);
        }
      }
    }
  }
  /* public function sanStr(string ...$kunci) : void
  {
    foreach ($kunci as $k) {
      if (isset($this->input[$k])) {
        $this->input[$k] = htmlspecialchars($this->input[$k]);
      }
    }
  } */
  /* public function sanBool(string ...$kunci) : void
  {
    foreach ($kunci as $k) {
      if (isset($this->input[$k]))
        $this->input[$k] = (int) filter_var($this->input[$k], FILTER_VALIDATE_BOOL);
    }
  } */
  public function check() : bool
  {
    foreach ($this->input as $i) {
      if (empty($i)) return false;
    }
    return true;
  }
  public function param() : array
  {
    return [
      implode(', ', array_map(fn($k) => "`$k`", array_keys($this->input))),
      implode(', ', array_map(fn($k) => ":$k", array_keys($this->input))),
      implode(', ', array_map(fn($k) => "`$k`=:$k", array_keys($this->input)))
    ];
  }
  public function bind(PDOStatement $prep) : void
  {
    foreach ($this->input as $kunci => $isi) {
      $jenis = match (gettype($isi)) {
        'boolean' => PDO::PARAM_BOOL,
        'integer' => PDO::PARAM_INT,
        'NULL' => PDO::PARAM_NULL,
        default => PDO::PARAM_STR
      };

      $prep->bindValue($kunci, $isi, $jenis);
    }
  }
}
