<?php

namespace App\Presentation\Controller\Api\V1\Plan\Add\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $name,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $description,
    ) {
    }

}
