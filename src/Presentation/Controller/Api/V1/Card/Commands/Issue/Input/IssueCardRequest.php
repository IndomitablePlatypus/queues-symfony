<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Issue\Input;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Symfony\Component\Validator\Constraints as Assert;

class IssueCardRequest
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
        private ?string $cardId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $customerId,
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

    public function getCardId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->cardId);
    }

    public function getCustomerId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->customerId);
    }
}
