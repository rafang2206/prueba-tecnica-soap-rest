<?php

declare(strict_types=1);

namespace App\Data\Mongo\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\Document(collection: 'buys')]
class Buy
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field]
    public string $amount;

    #[ODM\Field]
    public string $sessionId;

    #[ODM\Field]
    public string $code;

    #[ODM\Field]
    public string $createdAt;

    #[ODM\Field]
    public string $updateAt;

    #[ODM\Field]
    #[Assert\Choice(
        choices: ['pending', 'completed', 'rejected'],
        message: 'The state must be: {{ choices | join(", ") }}.'
    )]
    public string $status;

    #[ODM\ReferenceOne(targetDocument: User::class)]
    public ?User $user = null;
}
