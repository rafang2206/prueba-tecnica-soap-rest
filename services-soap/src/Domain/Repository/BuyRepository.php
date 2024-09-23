<?php

namespace App\Domain\Repository;

use App\Data\Mongo\Document\Buy;

abstract class BuyRepository {
  abstract function create($user, $data);
  abstract function getBySession($session): ?Buy;
  abstract function update($session, $status);
}

?>