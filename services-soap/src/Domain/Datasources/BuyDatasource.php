<?php

namespace App\Domain\Datasources;

use App\Data\Mongo\Document\Buy;

abstract class BuyDatasource {
  abstract function create($user, $data);
  abstract function getBySession($session): ?Buy;
  abstract function update($session, $status);
}

?>