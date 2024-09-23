<?php

namespace App\Domain\Repository;

use App\Domain\Entities\WalletEntity;

abstract class WalletRepository {
  abstract function rechargeBalance($user, $amount);
  abstract function getBalance($user): ?WalletEntity;
  abstract function createWallet($user);
  abstract function discountBalance($user, $amount);
}
?>