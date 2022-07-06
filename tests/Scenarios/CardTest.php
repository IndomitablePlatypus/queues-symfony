<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use Symfony\Component\HttpFoundation\Response;

class CardTest extends BaseScenarioTest
{
    public function test_collaborator_can_issue_card(): void
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);

        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $planId = $plans[0]['planId'];

        $card = $this->rPost(RouteName::ISSUE_CARD,
            ['workspaceId' => $workspaceId],
            ['planId' => $planId, 'customerId' => $customer->getId()]
        );

        $this->assertNotEmpty($card);
        $this->assertEquals($card['customerId'], $customer->getId());
        $this->assertEquals($card['planId'], $planId);

        $this->tokenize($customer);
        $this->rGet(RouteName::CUSTOMER_CARD, [
            'workspaceId' => $workspaceId,
            'cardId' => $card['cardId'],
        ]);
    }

    public function test_non_collaborator_cannot_issue_cards()
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $nonCollaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator2']);
        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);

        $this->tokenize($keeper);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $planId = $plans[0]['planId'];

        $this->tokenize($nonCollaborator);
        $this->rPost(RouteName::ISSUE_CARD,
            ['workspaceId' => $workspaceId],
            ['planId' => $planId, 'customerId' => $customer->getId()]
        );

        $this->assertNotFound();
    }

    public function test_card_is_satisfied_on_completion()
    {
        $collaborator = $this->getUserRepository()->findOneBy(['username' => 'collaborator1']);
        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);

        $this->tokenize($collaborator);

        $workspaces = $this->rGet(RouteName::GET_WORKSPACES);
        $workspaceId = $workspaces[0]['workspaceId'];

        $plans = $this->rGet(RouteName::GET_PLANS, ['workspaceId' => $workspaceId]);
        $planId = $plans[0]['planId'];

        $card = $this->rPost(RouteName::ISSUE_CARD,
            ['workspaceId' => $workspaceId],
            ['planId' => $planId, 'customerId' => $customer->getId()]
        );

        $routeArgs = ['workspaceId' => $workspaceId, 'cardId' => $card['cardId']];

        foreach ($card['requirements'] as $requirement) {
            $this->rPost(RouteName::NOTE_ACHIEVEMENT, $routeArgs, [
                'achievementId' => $requirement['requirementId'],
                'description' => $requirement['description'],
            ]);
        }

        $card = $this->rGet(RouteName::GET_CARD, $routeArgs);
        $this->assertTrue($card['isSatisfied']);

        $this->rPut(RouteName::BLOCK_CARD, $routeArgs);
        $this->assertResponseSucceeded();

        $this->rPut(RouteName::UNBLOCK_CARD, $routeArgs);
        $this->assertResponseSucceeded();

        $this->rPut(RouteName::COMPLETE_CARD, $routeArgs);

        $card = $this->rGet(RouteName::GET_CARD, $routeArgs);
        $this->assertTrue($card['isCompleted']);

        $this->rPut(RouteName::BLOCK_CARD, $routeArgs);
        $this->assertResponseCode(Response::HTTP_BAD_REQUEST);
    }

}
