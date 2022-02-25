<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Card;
use App\Domain\Entity\User;
use App\Infrastructure\Support\ArrayPresenterTrait;
use JsonSerializable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: "Profile",
    description: "Customer Profile",
    required: [
        "profileId",
        "name",
        "phone",
    ],
)]
class Profile implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[Property(description: "Profile Id", format: "uuid", nullable: false)]
        public string $profileId,

        #[Property(description: "Customer name", example: "Lucas Al Capone", nullable: false)]
        public string $name,

        #[Property(description: "Customer Phone", example: "1-800-984-3672", nullable: false)]
        public string $phone,
    ) {
    }

    public static function of(User $user): static
    {
        return new static(
            (string) $user->getId(),
            $user->getName(),
            $user->getUsername(),
        );
    }

    public function jsonSerialize(): array
    {
        return $this->_toArray(publicOnly: true);
    }

}
