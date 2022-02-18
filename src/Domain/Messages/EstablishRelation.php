<?php

namespace App\Domain\Messages;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\RelationType;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\GuidBasedImmutableId;

class EstablishRelation implements AsycMessageInterface
{
    use ArrayPresenterTrait;

    public function __construct(
        public GenericIdInterface $collaboratorId,
        public GenericIdInterface $workspaceId,
        public RelationType $relationType,
    ) {
    }

    public static function of(
        GenericIdInterface $collaboratorId,
        GenericIdInterface $workspaceId,
        RelationType $relationType,
    ) {
        return new self($collaboratorId, $workspaceId, $relationType);
    }

    private function restore(string $collaboratorId, string $workspaceId, string $relationType): self
    {
        $this->collaboratorId = GuidBasedImmutableId::of($collaboratorId);
        $this->workspaceId = GuidBasedImmutableId::of($workspaceId);
        $this->relationType = RelationType::of($relationType);
        return $this;
    }

    public function __serialize(): array
    {
        return $this->_toArray();
    }

    public function __unserialize(array $data): void
    {
        $this->restore(...$data);
    }

}
