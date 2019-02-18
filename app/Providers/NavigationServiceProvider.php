<?php

namespace App\Providers;

use App\Http\Front\Controllers\EventAdmin\EventsController;
use App\Http\Front\Controllers\EventAdmin\EventsController as EventAdminEventsController;
use App\Http\Front\Controllers\EventAdmin\SlotsController;
use App\Http\Front\Controllers\EventAdmin\TracksController;
use App\Http\Front\Controllers\Events\AttendingEventListController;
use App\Http\Front\Controllers\Events\PastEventsListController;
use App\Http\Front\Controllers\Events\AllEventsController;
use App\Http\Front\Controllers\Events\ShowEventFeedbackController;
use App\Http\Front\Controllers\Events\ShowEventScheduleController;
use App\Http\Front\Controllers\Events\SpeakingAtEventsListController;
use App\Http\Front\Controllers\Profile\ChangePasswordController;
use App\Http\Front\Controllers\Profile\ProfileController;
use App\Models\Event;
use Illuminate\Support\ServiceProvider;

use Spatie\Menu\Laravel\Menu;

class NavigationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Menu::macro('main', function () {
            return Menu::new()
                ->actionIf(
                    optional(auth()->user())->organisesEvents(),
                    [EventsController::class, 'index'],
                    'Organizing',
                    )
                ->actionIf(
                    optional(auth()->user())->speaksAtEvents(),
                    SpeakingAtEventsListController::class,
                    'Speaking',
                    )
                ->actionIf(
                    optional(auth()->user())->attendsEvents(),
                    AttendingEventListController::class,
                    'Attending',
                    );
        });

        Menu::macro('event', function (Event $event) {
            return Menu::new()
                ->action(ShowEventScheduleController::class, 'Schedule', $event->idSlug())
                ->action(ShowEventFeedbackController::class, 'Feedback', $event->idSlug());
        });

        Menu::macro('eventAdmin', function (Event $event) {
            return Menu::new()
                ->action([EventAdminEventsController::class, 'edit'], 'Details', $event)
                ->action([TracksController::class, 'index'], 'Tracks', $event)
                ->action([SlotsController::class, 'index'], 'Slots', $event);
        });

        Menu::macro('profile', function () {
            return Menu::new()
                ->action([ProfileController::class, 'show'], 'Profile')
                ->action([ChangePasswordController::class, 'show'], 'Change password');
        });
    }
}
