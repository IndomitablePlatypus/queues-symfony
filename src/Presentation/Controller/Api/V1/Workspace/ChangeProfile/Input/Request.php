<?php

namespace App\Presentation\Controller\Api\V1\Workspace\ChangeProfile\Input;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\WorkspaceProfile;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Symfony\Component\Validator\Constraints as Assert;

final class Request
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private  ?string $workspaceId,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private  ?string $name,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private  ?string $description,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private  ?string $address,
    ) {
    }

    public function getWorkspaceId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->workspaceId);
    }

    public function getProfile(): WorkspaceProfile
    {
        return WorkspaceProfile::of(
            $this->name,
            $this->description,
            $this->address,
        );
    }
}
