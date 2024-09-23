<?php

namespace App\Domain\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

class WalletRechargeDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $document,

        #[Assert\NotBlank(message: "The phone number must not be empty")]
        #[Assert\Type('integer', message: "The phone number must be a valid integer.")]
        public int $phone,

        #[Assert\NotBlank(message: "The amount must not be empty")]
        #[Assert\Positive(message: "The amount must be a positive number")]
        #[Assert\GreaterThanOrEqual(
            value: 10,
            message: "The amount must have at least {{ compared_value }} dollars"
        )]
        #[Assert\Type('integer', message: "The amount must be a valid integer.")]
        public int $amount,
    ) {
    }
}


?>