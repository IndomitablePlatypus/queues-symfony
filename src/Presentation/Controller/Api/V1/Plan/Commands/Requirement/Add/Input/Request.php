<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Requirement\Add\Input;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Symfony\Component\Validator\Constraints as Assert;

class Request
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
        private ?string $requirementId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $description,
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

    public function getRequirementId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->requirementId);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

}
