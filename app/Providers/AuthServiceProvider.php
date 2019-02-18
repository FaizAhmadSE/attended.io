<?php

namespace App\Providers;

use App\Domain\Event\Models\Event;
use App\Domain\Review\Models\Review;
use App\Domain\Slot\Models\Slot;
use App\Domain\Slot\Models\SlotOwnershipClaim;
use App\Domain\User\Models\User;
use App\Policies\EventPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SlotOwnershipClaimPolicy;
use App\Policies\SlotPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class,
        Review::class => ReviewPolicy::class,
        Slot::class => SlotPolicy::class,
        SlotOwnershipClaim::class => SlotOwnershipClaimPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function (User $user) {
            if ($user->admin) {
                return true;
            }
        });
    }
}
