<?php

namespace App\Tests\Feature\Business;

use App\Config\Routing\RouteName;
use App\Domain\Entity\Plan;
use App\Domain\Entity\Workspace;
use App\Infrastructure\Repository\PlanRepository;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Repository\WorkspaceRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CardTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $router = static::getContainer()->get(UrlGeneratorInterface::class);
        $userRepo = static::getContainer()->get(UserRepository::class);
        $workspaceRepo = static::getContainer()->get(WorkspaceRepository::class);
        $planRepo = static::getContainer()->get(PlanRepository::class);

        $keeper = $userRepo->findOneBy(['username' => 'keeper1']);
        $customer = $userRepo->findOneBy(['username' => 'customer1']);
        /** @var Workspace $workspace */
        $workspace = $keeper->getWorkspaces()[0];
        /** @var Plan $plan */
        $plan = $workspace->getPlans()[0];

        $client->loginUser($keeper);

        $cardResponse = $client->request('POST',
            $router->generate(RouteName::ISSUE_CARD, ['workspaceId' => $workspace->getId()]),
            [
                'customerId' => $customer->getId(),
                'planId' => $plan->getId(),
            ]);

        self::assertResponseIsSuccessful();
    }

}
