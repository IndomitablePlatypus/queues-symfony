<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use Symfony\Component\HttpFoundation\Response;

class InviteTest extends BaseScenarioTest
{
    public function test_invite_can_be_proposed_and_discarded_by_keeper()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $routeArgs = ['workspaceId' => $workspaceId];

        $inviteId = $this->rPost(RouteName::PROPOSE_INVITE, $routeArgs);
        $this->assertResponseSucceeded();
        $this->assertNotEmpty($inviteId);

        $routeArgs = ['workspaceId' => $workspaceId, 'inviteId' => $inviteId];
        $this->rDelete(RouteName::DISCARD_INVITE, $routeArgs);
        $this->assertResponseSucceeded();
    }

    public function test_invite_can_be_accepted()
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
        $this->assertResponseSucceeded();

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $this->assertNotEmpty($workspaces);
        $this->assertEquals($workspaceId, $workspaces[0]['workspaceId']);
    }

    public function test_invite_cannot_be_proposed_by_non_keeper()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator2']);
        $this->tokenize($collaborator);

        $routeArgs = ['workspaceId' => $workspaceId];

        $this->rPost(RouteName::PROPOSE_INVITE, $routeArgs);
        $this->assertNotFound();
    }

}
