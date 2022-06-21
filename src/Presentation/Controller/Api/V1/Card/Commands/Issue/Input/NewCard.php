<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Issue\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'New card request',
    required: ['planId', 'customerId'],
    properties: [
        new OA\Property(
            property: 'planId',
            description: 'Plan id',
            type: 'string',
            format: 'uuid',
            nullable: false,
        ),
        new OA\Property(
            property: 'customerId',
            description: 'Customer id',
            type: 'string',
            format: 'uuid',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class NewCard
{
}
