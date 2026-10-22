<?php
namespace App\Models;

class Drink{

  public function __construct(
    public ?int $id,
    public string $name,
    public int $caffeineMg,
    public int $volumeMl
  ){}
  
}