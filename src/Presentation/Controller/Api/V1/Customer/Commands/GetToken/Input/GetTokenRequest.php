<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    description: 'New API access token for the specific device request',
    required: ['identity', 'password', 'deviceName'],
    type: 'object',
)]
class GetTokenRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(
            description: 'Identity is either a phone or an email',
            example: 'robel.carter@satterfield.com',
            nullable: false
        )]
        public ?string $identity,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(
            description: 'Password',
            format: 'password',
            example: '[h*KrV|TiUx\\38-F\"HJ,',
            nullable: false
        )]
        public ?string $password,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(
            description: 'Device name is required to distinguish between different access tokens',
            example: 'Primary mobile device',
            nullable: false
        )]
        public ?string $deviceName,
    ) {
    }

}
