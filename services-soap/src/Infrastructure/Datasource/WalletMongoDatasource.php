<?php

namespace App\Infrastructure\Datasource;

use App\Data\Mongo\Document\Wallet;
use App\Domain\Datasources\WalletDatasource;
use App\Domain\Entities\WalletEntity;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

class WalletMongoDatasource extends WalletDatasource {

  private DocumentManager $dm;
  private LoggerInterface $logger;

  public function __construct(DocumentManager $dm, LoggerInterface $logger)
  {
      $this->dm = $dm;
      $this->logger = $logger;
  }

  function rechargeBalance($user, $amount){
    $wallet = $this->dm->getRepository(Wallet::class)->findOneBy(['user' => $user]);

    if (!$wallet) {
        throw new \Exception("Wallet no encontrada para el usuario con ID: $user");
    }

    // Actualizar el balance
    $wallet->balance += $amount;

    $this->dm->persist($wallet);
    $this->dm->flush();

    return $wallet;
  }

  function getBalance($user): ?WalletEntity {
    $balanceInfo = $this->dm->getRepository(Wallet::class)->findOneBy(['user' => $user]);
    $data = array("balance" => $balanceInfo->balance);
    $wallet = new WalletEntity($data);
    return $wallet;
  }

  function createWallet($user){
    $wallet = new Wallet();
    $wallet->balance = 0;
    $wallet->user = $user;

    $this->dm->persist($wallet);
    
    $this->dm->flush();
  }

  function discountBalance($user, $amount){
    $wallet = $this->dm->getRepository(Wallet::class)->findOneBy(['user' => $user]);

    // Actualizar el balance
    $wallet->balance -= $amount;

    $this->dm->persist($wallet);
    $this->dm->flush();
  }
}

?>