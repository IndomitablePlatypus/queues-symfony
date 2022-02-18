<?php

namespace App\Domain\Messages;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\GuidBasedImmutableId;

class ClearTokens implements AsycMessageInterface
{
    use ArrayPresenterTrait;

    public function __construct(
        public GenericIdInterface $userId,
        public ?string $tokenName = null,
    ) {
    }

    public static function of(
        GenericIdInterface $userId,
        ?string $deviceName = null
    ) {
        return new self($userId, $deviceName);
    }

    private function restore(string $userId, ?string $tokenName = null): self
    {
        $this->userId = GuidBasedImmutableId::of($userId);
        $this->tokenName = $tokenName;
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
