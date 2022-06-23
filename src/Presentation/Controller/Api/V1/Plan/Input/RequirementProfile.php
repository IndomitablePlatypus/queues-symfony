<?php

namespace App\Presentation\Controller\Api\V1\Plan\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Requirement profile request',
    required: ['description'],
    properties: [
        new OA\Property(
            property: 'description',
            description: 'Requirement description',
            type: 'string',
            example: 'Id quae excepturi sit vero velit est. Sapiente culpa ut assumenda non itaque officia. Reiciendis ducimus modi quaerat eius amet. Vero qui similique minima a nulla illum.',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class RequirementProfile
{
}
