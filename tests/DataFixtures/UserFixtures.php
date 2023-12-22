<?php

namespace App\Tests\DataFixtures;

use App\Domain\Entity\User;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    use FakerTrait;

    public const USER_NAMES = [
        'keeper1',
        'keeper2',
        'collaborator1',
        'collaborator2',
        'customer1',
        'customer2',
        'customer3',
    ];

    public static function getKeeperNames(): array
    {
        return array_slice(self::USER_NAMES, 0, 2);
    }

    public static function getCollaboratorNames(): array
    {
        return array_slice(self::USER_NAMES, 2, 2);
    }

    public function load(ObjectManager $manager)
    {
        $self = $this;

        array_map(static function ($name) use ($self, $manager): void {
            $user = (new User())
                ->setId(GuidBasedImmutableId::make())
                ->setUsername($name)
                ->setName($self->faker()->name())
                ->setPassword($self->faker()->password());

            $self->addReference($name, $user);
            $manager->persist($user);
        },
            self::USER_NAMES
        );

        $manager->flush();
    }

}
