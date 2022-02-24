<?php

namespace App\Domain\Messages;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\GuidBasedImmutableId;

class RequirementsChanged implements AsyncMessageInterface
{
    use ArrayPresenterTrait;

    public function __construct(
        public GenericIdInterface $planId,
    ) {
    }

    public static function of(
        GenericIdInterface $planId,
    ) {
        return new self($planId);
    }

    private function restore(
        string $planId,
    ): self {
        $this->planId = GuidBasedImmutableId::of($planId);
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
