<?php

declare(strict_types=1);

namespace App\Data\Mongo\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'wallets')]
class Wallet
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field]
    public int $balance;

    #[ODM\ReferenceOne(targetDocument: User::class)]
    public ?User $user = null;
}
