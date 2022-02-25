<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Card;
use JsonSerializable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;

class IssuedCards implements JsonSerializable
{
    /** @var Card[] */
    #[Property(
        description: "All of the customer's issued cards",
        type: "array",
        items: new Items(ref: "#/components/schemas/IssuedCard"),
        nullable: false,
    )]
    public array $cards;

    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    public static function of(Card ...$cards): static
    {
        return new static($cards);
    }

    public function jsonSerialize(): array
    {
        return [
            ...array_map(fn($card) => IssuedCard::of($card)->jsonSerialize(), $this->cards),
        ];
    }

}
