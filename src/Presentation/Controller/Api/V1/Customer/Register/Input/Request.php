<?php

namespace App\Presentation\Controller\Api\V1\Customer\Register\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public ?string $phone;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public ?string $name;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotCompromisedPassword]
    public ?string $password;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public ?string $deviceName;

    public function __construct(
        ?string $phone,
        ?string $name,
        ?string $password,
        ?string $deviceName,
    )
    {
        $this->phone = $phone;
        $this->name = $name;
        $this->password = $password;
        $this->deviceName = $deviceName;
    }

}
