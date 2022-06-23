<?php

namespace App\Presentation\Controller\Api\V1\Plan\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Plan profile request',
    required: ['name', 'description'],
    properties: [
        new OA\Property(
            property: 'name',
            description: 'Plan name',
            type: 'string',
            example: 'Mollitia id eos debitis qui aut.',
            nullable: false,
        ),
        new OA\Property(
            property: 'description',
            description: 'Plan description',
            type: 'string',
            example: 'Nobis qui quam dolore accusamus neque laborum ut doloremque. Cupiditate molestiae qui culpa et sint aut. Ut eveniet delectus et. Debitis quos hic qui rerum.',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class PlanProfile
{
}
