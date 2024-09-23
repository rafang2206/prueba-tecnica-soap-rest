<?php

declare(strict_types=1);

namespace App\Data\Mongo\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'users')]
class User
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field]
    public string $document;

    #[ODM\Field]
    public string $name;

    #[ODM\Field]
    public string $email;

    #[ODM\Field]
    public string $phone;

    #[ODM\Field]
    public string $createdAt;

    #[ODM\Field]
    public string $updateAt;
}
