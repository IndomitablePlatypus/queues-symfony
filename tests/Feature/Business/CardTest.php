<?php

namespace App\Tests\Feature\Business;

use App\Config\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;

class CardTest extends WebTestCase
{

    public function testSomething(): void
    {
        $client = static::createClient();
        $router = static::getContainer()->get(UrlGeneratorInterface::class);

        $crawler = $client->request('GET', $router->generate(RouteName::CUSTOMER_WORKSPACES));

        $this->assertResponseIsSuccessful();
    }


}
