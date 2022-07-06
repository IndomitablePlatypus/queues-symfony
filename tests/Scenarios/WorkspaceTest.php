<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use App\Tests\DataFixtures\FakerTrait;

class WorkspaceTest extends BaseScenarioTest
{
    use FakerTrait;

    public function test_workspace_can_be_kept()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspace = $this->rPost(RouteName::ADD_WORKSPACE, [], [
            'name' => $this->faker()->company(),
            'description' => $this->faker()->text(),
            'address' => $this->faker()->address(),
        ]);

        $this->assertResponseSucceeded();

        $this->assertEquals($workspace['keeperId'], $keeper->getId());

        $changed = 'Changed';
        $workspace = $this->rPut(RouteName::CHANGE_PROFILE,
            ['workspaceId' => $workspace['workspaceId']],
            ['name' => $changed, 'description' => $changed, 'address' => $changed],
        );

        $this->assertResponseSucceeded();

        $this->assertEquals(
            [
                'name' => $changed,
                'description' => $changed,
                'address' => $changed,
            ],
            [
                'name' => $workspace['name'],
                'description' => $workspace['description'],
                'address' => $workspace['address'],
            ]
        );
    }
}
