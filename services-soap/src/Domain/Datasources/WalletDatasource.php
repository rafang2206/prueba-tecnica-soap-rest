<?php

namespace App\Domain\Datasources;

use App\Domain\Entities\WalletEntity;

abstract class WalletDatasource {
  abstract function rechargeBalance($user, $amount);
  abstract function getBalance($user): ?WalletEntity;
  abstract function createWallet($user);
  abstract function discountBalance($user, $amount);
}
?>