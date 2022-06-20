<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Launch\Input;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\PlanProfile;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraints as Assert;

class LaunchPlanRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $workspaceId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $planId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\DateTime]
        #[Assert\GreaterThan('+1 days')]
        private ?string $expirationDate,
    ) {
    }

    public function getWorkspaceId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->workspaceId);
    }

    public function getPlanId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->planId);
    }

    public function getExpirationDate(): Carbon
    {
        return new Carbon($this->expirationDate);
    }

}
