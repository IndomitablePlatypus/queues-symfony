<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\User;

interface KeeperRepositoryInterface
{
    public function take(GenericIdInterface $keeperId): User;
}
