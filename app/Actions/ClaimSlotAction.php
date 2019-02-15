<?php

namespace App\Actions;

use App\Models\Slot;
use App\Models\User;
use App\Notifications\ReviewSlotClaimNotification;

class ClaimSlotAction
{
    public function execute(User $claimingUser, Slot $slot)
    {
        $slot->claimingUsers()->attach($claimingUser);

        activity()
            ->performedOn($slot)
            ->log("{$claimingUser->email} is claming slot `{$slot->name}`");

        $slot->event->owners->each->notify(new ReviewSlotClaimNotification($claimingUser, $slot));
    }
}
