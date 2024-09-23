<?php

namespace App\Infrastructure\Datasource;

use App\Data\Mongo\Document\Buy;
use App\Domain\Datasources\BuyDatasource;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

class BuyMongoDatasource extends BuyDatasource {

  private DocumentManager $dm;
  private LoggerInterface $logger;

  public function __construct(DocumentManager $dm, LoggerInterface $logger)
  {
      $this->dm = $dm;
      $this->logger = $logger;
  }

  function create($user, $data){
    $buy = new Buy();
    $buy->amount = $data['amount'];
    $buy->sessionId = $data['sessionId'];
    $buy->code = $data['code'];
    $buy->createdAt = date("Y-m-d H:i:s");
    $buy->updateAt = date("Y-m-d H:i:s");
    $buy->status = 'pending';
    $buy->user = $user;

    $this->dm->persist($buy);
    
    $this->dm->flush();
  }

  function getBySession($session): ?Buy {
    $buy = $this->dm->getRepository(Buy::class)->findOneBy([
      'sessionId' => $session,
      'status' => 'pending'
    ]);
    
    if (!$buy) {
      return null;
    }

    return $buy;
  }

  function update($session, $status){
    $buy = $this->dm->getRepository(Buy::class)->findOneBy([
      'sessionId' => $session,
      'status' => 'pending'
    ]);

    $buy->sessionId = '';
    $buy->status = $status;

    $this->dm->persist($buy);
    
    $this->dm->flush();
  }
}

?>