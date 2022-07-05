<?php

namespace App\Tests\Feature\Business;

use App\Config\Routing\RouteName;
use App\Domain\Entity\Plan;
use App\Domain\Entity\Workspace;
use App\Tests\BaseScenarioTest;

class CardTest extends BaseScenarioTest
{
    public function test_collaborator_can_issue_card(): void
    {
        $keeper = $this->getUserRepository()->findOneBy(['username' => 'keeper1']);
        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);

        /** @var Workspace $workspace */
        $workspace = $keeper->getWorkspaces()[0];

        /** @var Plan $plan */
        $plan = $workspace->getPlans()[0];

        $this->tokenize($keeper);

        $this->rPost(
            RouteName::ISSUE_CARD,
            ['workspaceId' => $workspace->getId()],
            [
                'customerId' => $customer->getId(),
                'planId' => $plan->getId(),
            ]
        );

        $this->assertResponseIsSuccessful();

        $this->assertJsonResponseContainsKey('cardId');

        $cardId = $this->responseValue('cardId');

        $this->tokenize($customer);
        $this->rGet(RouteName::CUSTOMER_CARD, [
            'workspaceId' => $workspace->getId(),
            'cardId' => $cardId,
        ]);

        $this->assertResponseIsSuccessful();
    }

}
