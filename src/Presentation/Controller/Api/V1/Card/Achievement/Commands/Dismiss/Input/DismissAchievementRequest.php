<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss\Input;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Symfony\Component\Validator\Constraints as Assert;

class DismissAchievementRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $workspaceId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $cardId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $achievementId,
    ) {
    }

    public function getWorkspaceId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->workspaceId);
    }

    public function getCardId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->cardId);
    }

    public function getAchievementId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->achievementId);
    }
}
