<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $identity,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $password,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $deviceName,
    ) {
    }

}
