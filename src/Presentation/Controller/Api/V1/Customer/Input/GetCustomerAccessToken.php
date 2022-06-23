<?php

namespace App\Presentation\Controller\Api\V1\Customer\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'New API access token for the specific device request',
    required: ['identity', 'password', 'deviceName'],
    properties: [
        new OA\Property(
            property: 'identity',
            description: 'Identity',
            type: 'string',
            example: '(224) 733-9480',
            nullable: false,
        ),
        new OA\Property(
            property: 'password',
            description: 'Password',
            type: 'string',
            format: 'password',
            example: '[h*KrV|TiUx\\38-F\"HJ,',
            nullable: false
        ),
        new OA\Property(
            property: 'deviceName',
            description: 'Device name is required to distinguish between different access tokens',
            type: 'string',
            example: 'Primary mobile device',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class GetCustomerAccessToken
{
}
