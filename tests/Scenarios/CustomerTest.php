<?php

namespace App\Tests\Scenarios;

use App\Config\Routing\RouteName;
use App\Tests\DataFixtures\FakerTrait;

class CustomerTest extends BaseScenarioTest
{
    use FakerTrait;

    public function test_customer_can_register()
    {
        $token = $this->rPost(RouteName::REGISTER, [], [
            'phone' => $this->faker()->phoneNumber(),
            'name' => $this->faker()->name(),
            'password' => $this->faker()->password(),
            'deviceName' => $this->faker()->word(),
        ]);
        $this->assertMatchesRegularExpression('/\d+\|.{40}/', $token);
    }

    public function test_customer_can_login()
    {
        $password = $this->faker()->password();
        $identity = $this->faker()->phoneNumber();

        $this->rPost(RouteName::REGISTER, [], [
            'phone' => $identity,
            'name' => $this->faker()->name(),
            'password' => $password,
            'deviceName' => $this->faker()->word(),
        ]);

        $this->assertResponseIsSuccessful();

        $token = $this->rPost(RouteName::GET_TOKEN, [], [
            'identity' => $identity,
            'password' => $password,
            'deviceName' => $this->faker()->word(),
        ]);

        $this->assertMatchesRegularExpression('/\d+\|.{40}/', $token);
    }

    public function test_customer_can_get_id()
    {
        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);
        $this->tokenize($customer);

        $id = $this->rGet(RouteName::CUSTOMER_ID);
        $this->assertEquals($id, $customer->getId());
    }

    public function test_customer_can_get_profile()
    {
        $customer = $this->getUserRepository()->findOneBy(['username' => 'customer1']);
        $this->tokenize($customer);

        $this->rGet(RouteName::CUSTOMER_PROFILE);

        $this->assertEquals($this->responseValue('profileId'), $customer->getId());
        $this->assertEquals($this->responseValue('name'), $customer->getName());
        $this->assertEquals($this->responseValue('phone'), $customer->getUsername());
    }
}
