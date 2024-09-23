<?php

namespace App\Domain\Entities;

class WalletEntity {
  public int $balance;

  public function __construct($data)
  {
    $this->balance = $data['balance'];
  }
}

?>