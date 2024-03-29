<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterCustomerRequest
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
        public ?string $password,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public ?string $deviceName,

    ) {
    }

}
