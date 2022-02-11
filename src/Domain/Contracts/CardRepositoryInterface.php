<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Card;

interface CardRepositoryInterface
{
    public function persist(Card $card): Card;
}
