<?php

namespace App\Domain\Datasources;

use App\Data\Mongo\Document\User;
use App\Domain\Dtos\CreateUserDto;

abstract class UserDatasource {
  abstract function get_by_id($id): ?User;
  abstract function get_by_email($email): ?User;
  abstract function get_by_document($document): ?User;
  abstract function create(CreateUserDto $user): ?User;
}

?>