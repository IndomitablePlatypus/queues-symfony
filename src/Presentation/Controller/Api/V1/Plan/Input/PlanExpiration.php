<?php

namespace App\Presentation\Controller\Api\V1\Plan\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Plan expiration request',
    required: ['expirationDate'],
    properties: [
        new OA\Property(
            property: 'expirationDate',
            description: 'Plan expiration date',
            type: 'string',
            format: 'date-time',
            example: '2070-01-01T00:00:00.000Z',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class PlanExpiration
{
}
