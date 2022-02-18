<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $phone,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $name,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\NotCompromisedPassword]
        public ?string $password,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $deviceName,

    ) {
    }

}
