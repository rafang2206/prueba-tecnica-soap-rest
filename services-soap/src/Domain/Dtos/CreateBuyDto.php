<?php

namespace App\Domain\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBuyDto
{
    public function __construct(
        #[Assert\NotBlank(message: "The Document must not be empty")]
        public string $document,

        #[Assert\NotBlank(message: "The phone number must not be empty")]
        #[Assert\Positive(message: "The phone number must be a positive number")]
        #[Assert\Length(
            min: 7,
            max: 15,
            minMessage: "The phone number must have at least {{ limit }} digits",
            maxMessage: "The phone number cannot be more than {{ limit }} digits"
        )]
        public int $phone,

    ) {
    }
}

?>