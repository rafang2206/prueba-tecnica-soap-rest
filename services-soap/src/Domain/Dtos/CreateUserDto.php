<?php

namespace App\Domain\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $document,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank(message: "The email cannot be empty")]
        #[Assert\Email(
            message: 'The email {{ value }} is not a valid email.',
        )]
        public string $email,

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