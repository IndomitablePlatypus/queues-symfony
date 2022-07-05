<?php

namespace App\Tests\Feature\Business;

use App\Config\Routing\RouteName;
use App\Domain\Entity\Plan;
use App\Domain\Entity\Workspace;
use App\Tests\Helpers\AssertInJsonResponseTrait;
use App\Tests\Helpers\ClientTrait;
use App\Tests\Helpers\RepositoriesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardTest extends WebTestCase
{
    use AssertInJsonResponseTrait, RepositoriesTrait, ClientTrait;

    public function test_collaborator_can_issue_card(): void
    {
        static::init();

        $keeper = self::getUserRepository()->findOneBy(['username' => 'keeper1']);
        $customer = self::getUserRepository()->findOneBy(['username' => 'customer1']);

        /** @var Workspace $workspace */
        $workspace = $keeper->getWorkspaces()[0];

        /** @var Plan $plan */
        $plan = $workspace->getPlans()[0];

        self::withUser($keeper);

        self::rPost(
            RouteName::ISSUE_CARD,
            ['workspaceId' => $workspace->getId()],
            [
                'customerId' => $customer->getId(),
                'planId' => $plan->getId(),
            ]
        );

        self::assertResponseIsSuccessful();

        self::assertJsonResponseContainsKey('cardId');

        $cardId = self::val('cardId');

        self::withUser($customer);
        self::rGet(RouteName::CUSTOMER_CARD, [
            'workspaceId' => $workspace->getId(),
            'cardId' => $cardId,
        ]);

        self::assertResponseIsSuccessful();

    }

}
