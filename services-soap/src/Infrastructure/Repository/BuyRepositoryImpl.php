<?php


namespace App\Infrastructure\Repository;

use App\Data\Mongo\Document\Buy;
use App\Domain\Datasources\BuyDatasource;
use App\Domain\Repository\BuyRepository;
use App\Infrastructure\Datasource\BuyMongoDatasource;

class BuyRepositoryImpl extends BuyRepository {

  private BuyDatasource $dataSource;

  function __construct(BuyMongoDatasource $dataSource)
  {
    $this->dataSource = $dataSource;
  }

  function create($user, $data){
    return $this->dataSource->create($user, $data);
  }

  function getBySession($code): ?Buy {
    return $this->dataSource->getBySession($code);
  }

  function update($session, $status){
    return $this->dataSource->update($session, $status);
  }
}

?>