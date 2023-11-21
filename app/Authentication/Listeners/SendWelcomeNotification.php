<?php

namespace App\Authentication\Listeners;

use App\Authentication\Models\User;
use App\Authentication\Notifications\Welcome;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeNotification implements ShouldQueue
{
    /**
     * Handle that a user was registered.
     */
    public function handle(Registered $event): void
    {
        $event->user->notify($this->getNotification($event->user));
    }

    /**
     * Get a notification for the given user.
     */
    private function getNotification(User $user): Welcome
    {
        return new Welcome($user->createActivationToken());
    }
}
