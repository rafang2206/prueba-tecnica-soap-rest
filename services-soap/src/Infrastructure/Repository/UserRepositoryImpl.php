<?php

namespace App\Infrastructure\Repository;

use App\Data\Mongo\Document\User;
use App\Domain\Datasources\UserDatasource;
use App\Domain\Dtos\CreateUserDto;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Datasource\UserMongoDatasource;

class UserRepositoryImpl extends UserRepository {

  private UserDatasource $dataSource;

  function __construct(UserMongoDatasource $dataSource)
  {
    $this->dataSource = $dataSource;
  }

  public function get_by_id($id): ?User {
    return $this->dataSource->get_by_id($id);
  }

  public function get_by_email($email): ?User {
    return $this->dataSource->get_by_email($email);
  }

  public function get_by_document($document): ?User{
    return $this->dataSource->get_by_document($document);
  }

  public function create(CreateUserDto $user): ?User {
    return $this->dataSource->create($user);
  }
}

?>