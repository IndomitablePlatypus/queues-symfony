<?php

namespace App\Tests\DataFixtures;

use App\Domain\Dto\RelationType;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\Relation;
use App\Domain\Entity\User;
use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkspacesFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public const WORKSPACE_NAMES = [
        'workspace1',
        'workspace2',
    ];

    public function load(ObjectManager $manager)
    {
        $self = $this;

        array_map(static function ($name, $keeperName) use ($self, $manager): void {
            $keeper = $self->getKeeper($keeperName);

            $workspace = $self->makeWorkspace($keeper);
            $manager->persist($workspace);

            $relation = $self->makeRelation($keeper, $workspace);
            $manager->persist($relation);

            $self->addReference($name, $workspace);
        },
            self::WORKSPACE_NAMES,
            UserFixtures::getKeeperNames(),
        );

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    protected function getKeeper($name): User
    {
        /** @var User $user */
        $user = $this->referenceRepository->getReference($name);
        return $user;
    }

    protected function makeWorkspace(User $keeper): Workspace
    {
        return Workspace::create(
            GuidBasedImmutableId::make(),
            $keeper,
            WorkspaceProfile::of(
                $this->faker()->company(),
                $this->faker->text(),
                $this->faker->address(),
            ),
        );
    }

    protected function makeRelation(User $keeper, Workspace $workspace): Relation
    {
        return Relation::create(
            GuidBasedImmutableId::make(),
            $keeper,
            $workspace,
            RelationType::KEEPER(),
        );
    }
}
