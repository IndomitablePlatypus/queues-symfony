<?php

namespace App\Tests\DataFixtures;

use App\Domain\Dto\PlanProfile;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\Plan;
use App\Domain\Entity\User;
use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlansFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public const PLAN_NAMES = [
        'plan1',
        'plan2',
    ];

    public function load(ObjectManager $manager)
    {
        $self = $this;

        array_map(static function ($name, $workspaceName) use ($self, $manager): void {
            $plan = $self->makePlan($self->getWorkspace($workspaceName));
            $self->addReference($name, $plan);
            $manager->persist($plan);

            $plan->addRequirement(GuidBasedImmutableId::make(), $self->faker()->sentence());
            $plan->addRequirement(GuidBasedImmutableId::make(), $self->faker()->sentence());
            $plan->addRequirement(GuidBasedImmutableId::make(), $self->faker()->sentence());
        },
            self::PLAN_NAMES,
            WorkspacesFixtures::WORKSPACE_NAMES,
        );

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            WorkspacesFixtures::class,
        ];
    }

    protected function getWorkspace($name): Workspace
    {
        /** @var Workspace $workspace */
        $workspace = $this->referenceRepository->getReference($name);
        return $workspace;
    }

    protected function makePlan(Workspace $workspace): Plan
    {
        return Plan::create(
            GuidBasedImmutableId::make(),
            $workspace,
            PlanProfile::of(
                $this->faker()->sentence(),
                $this->faker()->text(),
            )
        );
    }

}
