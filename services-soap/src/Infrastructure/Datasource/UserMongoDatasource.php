<?php

namespace App\Infrastructure\Datasource;

use App\Data\Mongo\Document\User;
use App\Domain\Datasources\UserDatasource;
use App\Domain\Dtos\CreateUserDto;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

class UserMongoDatasource extends UserDatasource {

  private DocumentManager $dm;
  private LoggerInterface $logger;

  public function __construct(DocumentManager $dm, LoggerInterface $logger)
  {
      $this->dm = $dm;
      $this->logger = $logger;
  }

  public function get_by_id($id): ?User {
    return null;
  }

  public function get_by_email($email): ?User {
    $userEmail = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);

    if (!$userEmail) {
      return null;
    }

    return $userEmail;
  }

  public function get_by_document($document): ?User{

    $userDocument = $this->dm->getRepository(User::class)->findOneBy(['document' => $document]);
    
    if (!$userDocument) {
      return null;
    }

    return $userDocument;
  }

  public function create(CreateUserDto $userDto) : ?User {
    $user = new User();
    $user->document = $userDto->document;
    $user->name = $userDto->name;
    $user->email = $userDto->email;
    $user->phone = $userDto->phone;

    $this->dm->persist($user);
    
    $this->dm->flush();

    return $user;
  }
}

?>