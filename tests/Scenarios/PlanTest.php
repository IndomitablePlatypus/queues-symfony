<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use App\Tests\DataFixtures\FakerTrait;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class PlanTest extends BaseScenarioTest
{
    use FakerTrait;

    public function test_plan_can_be_added_by_keeper()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plan = $this->rPost(RouteName::ADD_PLAN,
            ['workspaceId' => $workspaceId],
            ['name' => $this->faker()->sentence(), 'description' =>  $this->faker()->text()]
        );

        $this->assertEquals($workspaceId, $plan['workspaceId']);
    }

    public function test_plan_can_be_added_by_collaborator()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plan = $this->rPost(RouteName::ADD_PLAN,
            ['workspaceId' => $workspaceId],
            ['name' => $this->faker()->sentence(), 'description' =>  $this->faker()->text()]
        );

        $this->assertEquals($workspaceId, $plan['workspaceId']);
    }

    public function test_plan_can_be_worked_on_by_collaborator()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $plan = $plans[0];
        $planId = $plan['planId'];

        $changed = 'Changed';

        $this->assertNotEquals($changed, $plan['name']);
        $this->assertNotEquals($changed, $plan['description']);
        $this->assertFalse($plan['isLaunched']);
        $this->assertFalse($plan['isStopped']);
        $this->assertFalse($plan['isArchived']);

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId];
        $routeParams = ['expirationDate' => (string) Carbon::now()->addDay()];

        $plan = $this->rPut(RouteName::LAUNCH_PLAN, $routeArgs, $routeParams);

        $this->assertNotEquals($changed, $plan['name']);
        $this->assertNotEquals($changed, $plan['description']);
        $this->assertTrue($plan['isLaunched']);
        $this->assertFalse($plan['isStopped']);
        $this->assertFalse($plan['isArchived']);

        $plan = $this->rPut(
            RouteName::CHANGE_PLAN_PROFILE, $routeArgs,
            ['name' => $changed, 'description' => $changed],
        );

        $this->assertEquals($changed, $plan['name']);
        $this->assertEquals($changed, $plan['description']);

        $plan = $this->rPut(RouteName::STOP_PLAN, $routeArgs);

        $this->assertFalse($plan['isLaunched']);
        $this->assertTrue($plan['isStopped']);
        $this->assertFalse($plan['isArchived']);

        $plan = $this->rPut(RouteName::ARCHIVE_PLAN, $routeArgs);
        $this->assertTrue($plan['isArchived']);
    }

    public function test_plan_cannot_be_accessed_by_stranger()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $planId = $plans[0]['planId'];

        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator2']);
        $this->tokenize($collaborator);

        $this->rPost(RouteName::ADD_PLAN, ['workspaceId' => $workspaceId], ['name' => 'name', 'description' => 'description']);
        $this->assertNotFound();

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId];
        $routeParams = ['expirationDate' => (string) Carbon::now()->addDay()];

        $this->rPut(RouteName::LAUNCH_PLAN, $routeArgs, $routeParams);
        $this->assertNotFound();

        $this->rPut(RouteName::STOP_PLAN, $routeArgs);
        $this->assertNotFound();

        $this->rPut(RouteName::ARCHIVE_PLAN, $routeArgs);
        $this->assertNotFound();
    }

    public function test_plan_can_be_relaunched()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $plan = $plans[0];
        $planId = $plan['planId'];

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId];
        $routeParams = ['expirationDate' => (string) Carbon::now()->addDay()];

        $plan = $this->rPut(RouteName::LAUNCH_PLAN, $routeArgs, $routeParams);
        $this->assertTrue($plan['isLaunched']);

        $routeParams = ['expirationDate' => (string) Carbon::now()->addCentury()];

        $plan = $this->rPut(RouteName::LAUNCH_PLAN, $routeArgs, $routeParams);
        $this->assertTrue($plan['isLaunched']);
    }

    public function test_plan_cannot_be_stopped_twice()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $plan = $plans[0];
        $planId = $plan['planId'];

        $routeArgs = ['workspaceId' => $workspaceId, 'planId' => $planId];
        $routeParams = ['expirationDate' => (string) Carbon::now()->addDay()];

        $plan = $this->rPut(RouteName::LAUNCH_PLAN, $routeArgs, $routeParams);
        $this->assertTrue($plan['isLaunched']);

        $plan = $this->rPut(RouteName::STOP_PLAN, $routeArgs);
        $this->assertTrue($plan['isStopped']);

        $this->rPut(RouteName::STOP_PLAN, $routeArgs);
        $this->assertResponseCode(Response::HTTP_BAD_REQUEST);
    }

}
