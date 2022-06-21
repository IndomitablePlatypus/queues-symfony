<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Achievement request',
    required: ['achievementId', 'description'],
    properties: [
        new OA\Property(
            property: 'achievementId',
            description: 'Achievement (requirement) id',
            type: 'string',
            format: 'uuid',
            nullable: false,
        ),
        new OA\Property(
            property: 'description',
            description: 'Achievement (requirement) description',
            type: 'string',
            example: 'Deserunt deleniti aut autem dolores tempore. Maxime cupiditate modi delectus. Sapiente a perferendis voluptatibus voluptates molestiae. Quibusdam totam molestiae ea repellendus dolorem.',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class Achievement
{
}
