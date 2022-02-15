<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\Invite;

interface InviteRepositoryInterface
{
    public function persist(Invite $invite): Invite;

    public function delete(GenericIdInterface $inviteId): void;

    public function take(GenericIdInterface $inviteId): Invite;
}
