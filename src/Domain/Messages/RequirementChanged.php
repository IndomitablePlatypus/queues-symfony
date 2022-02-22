<?php

namespace App\Domain\Messages;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\GuidBasedImmutableId;

class RequirementChanged implements AsyncMessageInterface
{
    use ArrayPresenterTrait;

    public function __construct(
        public GenericIdInterface $planId,
        public GenericIdInterface $requirementId,
        public string $description,
    ) {
    }

    public static function of(
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
        string $description,
    ) {
        return new self($planId, $requirementId, $description);
    }

    private function restore(
        string $planId,
        string $requirementId,
        string $description,
    ): self {
        $this->planId = GuidBasedImmutableId::of($planId);
        $this->requirementId = GuidBasedImmutableId::of($requirementId);
        $this->description = $description;
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
