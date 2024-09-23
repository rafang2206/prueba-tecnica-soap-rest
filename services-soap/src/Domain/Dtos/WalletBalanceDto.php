<?php

namespace App\Domain\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

class WalletBalanceDto
{
    public function __construct(
        #[Assert\NotBlank(message: "The Document must not be empty")]
        public string $document,

        #[Assert\NotBlank(message: "The phone number must not be empty")]
        public string $phone

    ) {
    }
}

?>