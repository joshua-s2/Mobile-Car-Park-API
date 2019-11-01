<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateUserSettingsColumn
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return false
     */
    public function handle(Registered $event)
    {
        // Create the settings column for the registered user
        // if user type is app user
        if ($event->user->role == 'user') {
            $settings['app_notifications'] = $settings['push_notifications'] = $settings['location_tracking'] = true;
            $event->user->settings()->create($settings);
        }

        return false;
    }
}
