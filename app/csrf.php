<?php
class CSRF
{
  private string $token;
  private string $input_name = '_token';

  public function __construct()
  {
    $this->token = bin2hex(random_bytes(32));
  }

  public function get() : string
  {
    return $this->token;
  }
  public function append() : string
  {
    return '<input type="hidden" name="' . $this->input_name . '" value="' . $this->token . '">';
  }
  public function equal(string $token2) : bool
  {
    return hash_equals($this->token, $token2);
  }
  public function verify(Form $input) : bool
  {
    $token = $input->get($this->input_name);
    $input->del($this->input_name);
    return $this->equal($token);
  }
}
