<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note\Input;

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
        private ?string $cardId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $achievementId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private ?string $description,
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

    public function getDescription(): string
    {
        return $this->description;
    }
}
