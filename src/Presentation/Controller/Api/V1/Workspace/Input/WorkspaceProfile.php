<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Input;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Workspace profile request',
    required: ['name', 'description', 'address'],
    properties: [
        new OA\Property(
            property: 'name',
            description: 'Workspace (business) title',
            type: 'string',
            example: 'Tillman-Schaefer',
            nullable: false,
        ),
        new OA\Property(
            property: 'description',
            description: 'Workspace (business) description',
            type: 'string',
            example: 'Qui et qui quia modi. Ut alias recusandae omnis amet mollitia. Ea eos consequatur minima consequuntur qui. Temporibus et unde sunt aut eum quia.',
            nullable: false,
        ),
        new OA\Property(
            property: 'address',
            description: 'Workspace (business) address',
            type: 'string',
            example: '7171 Ted Flats Suite 993\nLake Uriel, PA 41355-3662',
            nullable: false,
        ),
    ],
    type: 'object',
)]
class WorkspaceProfile
{
}
