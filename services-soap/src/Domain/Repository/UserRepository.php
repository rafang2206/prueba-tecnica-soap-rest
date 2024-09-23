<?php

namespace App\Domain\Repository;

use App\Data\Mongo\Document\User;
use App\Domain\Dtos\CreateUserDto;

abstract class UserRepository {
  abstract protected function get_by_id($id): ?User;
  abstract protected function get_by_email($email): ?User;
  abstract protected function get_by_document($document): ?User;
  abstract protected function create(CreateUserDto $user): ?User;
}

?>