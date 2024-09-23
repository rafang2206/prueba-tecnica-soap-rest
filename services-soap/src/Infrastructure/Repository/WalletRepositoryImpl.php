<?php

namespace App\Infrastructure\Repository;

use App\Domain\Datasources\WalletDatasource;
use App\Domain\Entities\WalletEntity;
use App\Domain\Repository\WalletRepository;
use App\Infrastructure\Datasource\WalletMongoDatasource;

class WalletRepositoryImpl extends WalletRepository {

  private WalletDatasource $dataSource;

  function __construct(WalletMongoDatasource $dataSource)
  {
    $this->dataSource = $dataSource;
  }

  function rechargeBalance($user, $amount){
    return $this->dataSource->rechargeBalance($user, $amount);
  }

  function getBalance($user): ?WalletEntity {
    return $this->dataSource->getBalance($user);
  }

  function createWallet($user)
  {
    return $this->dataSource->createWallet($user);
  }

  function discountBalance($user, $amount){
    return $this->dataSource->discountBalance($user, $amount);
  }
}

?>