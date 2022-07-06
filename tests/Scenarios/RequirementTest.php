<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use App\Tests\DataFixtures\FakerTrait;

class RequirementTest extends BaseScenarioTest
{
    use FakerTrait;

    public function test_requirement_can_be_worked_on_by_collaborator()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $plan = $plans[0];
        $planId = $plan['planId'];

        $count = count($plan['requirements']);

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId];
        $plan = $this->rPost(RouteName::ADD_PLAN_REQUIREMENT, $routeArgs, ['description' => $this->faker()->text()]);

        $this->assertCount($count+1, $plan['requirements']);

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId, 'requirementId' => $plan['requirements'][0]['requirementId']];
        $changed = 'Changed';
        $plan = $this->rPut(RouteName::CHANGE_PLAN_REQUIREMENT, $routeArgs, ['description' => $changed]);

        $this->assertEquals($changed, $plan['requirements'][0]['description']);

        $plan = $this->rDelete(RouteName::REMOVE_PLAN_REQUIREMENT, $routeArgs);

        $this->assertCount($count, $plan['requirements']);
    }


    public function test_requirement_cannot_be_accessed_by_by_stranger()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $secondCollaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator2']);

        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $plan = $plans[0];
        $planId = $plan['planId'];
        $requirement = $plan['requirements'][0];

        $this->tokenize($secondCollaborator);

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId];
        $this->rPost(RouteName::ADD_PLAN_REQUIREMENT, $routeArgs, ['description' => $this->faker()->text()]);
        $this->assertNotFound();

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId, 'requirementId' => $requirement['requirementId']];
        $this->rPut(RouteName::CHANGE_PLAN_REQUIREMENT, $routeArgs, ['description' => 'description']);
        $this->assertNotFound();
    }

}
