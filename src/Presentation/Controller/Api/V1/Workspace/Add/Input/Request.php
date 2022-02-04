<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Add\Input;

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

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $address,
    ) {
    }

}