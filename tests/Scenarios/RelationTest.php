<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use App\Domain\Dto\RelationType;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Tests\DataFixtures\FakerTrait;
use Symfony\Component\HttpFoundation\Response;

class RelationTest extends BaseScenarioTest
{
    use FakerTrait;

    public function test_relation_is_added_for_keeper()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspace = $this->rPost(RouteName::ADD_WORKSPACE, [], [
            'name' => $this->faker()->company(),
            'description' => $this->faker()->text(),
            'address' => $this->faker()->address(),
        ]);

        $relation = $this->getRelationRepository()->enquire($keeper->getId(), GuidBasedImmutableId::of($workspace['workspaceId']));

        $this->assertNotEmpty($relation);
        $this->assertTrue(RelationType::KEEPER()->equals($relation));
    }

    public function test_relation_is_added_for_member()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $routeArgs = ['workspaceId' => $workspaceId];
        $inviteId = $this->rPost(RouteName::PROPOSE_INVITE, $routeArgs);

        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);
        $this->tokenize($customer);

        $routeArgs = ['workspaceId' => $workspaceId, 'inviteId' => $inviteId];

        $this->rPut(RouteName::ACCEPT_INVITE, $routeArgs);

        $relation = $this->getRelationRepository()->enquire($customer->getId(), GuidBasedImmutableId::of($workspaceId));

        $this->assertNotEmpty($relation);
        $this->assertTrue(RelationType::MEMBER()->equals($relation));
    }

    public function test_keeper_cannot_leave_workspace()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $this->rPost(RouteName::LEAVE_RELATION, ['workspaceId' => $workspaceId, 'collaboratorId' => $keeper->getId()]);
        $this->assertResponseCode(Response::HTTP_BAD_REQUEST);
    }

    public function test_keeper_can_fire_collaborator()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);

        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $this->rPost(RouteName::FIRE_COLLABORATOR, ['workspaceId' => $workspaceId, 'collaboratorId' => $collaborator->getId()]);
        $this->assertResponseSucceeded();
    }

    public function test_collaborator_cannot_fire_collaborator()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $secondCollaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator2']);

        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $this->rPost(RouteName::FIRE_COLLABORATOR, ['workspaceId' => $workspaceId, 'collaboratorId' => $secondCollaborator->getId()]);
        $this->assertNotFound();
    }

}
