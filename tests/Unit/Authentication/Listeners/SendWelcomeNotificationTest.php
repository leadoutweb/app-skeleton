<?php

namespace Tests\Unit\Authentication\Listeners;

use App\Authentication\Listeners\SendWelcomeNotification;
use App\Authentication\Models\User;
use App\Authentication\Notifications\Welcome;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendWelcomeNotificationTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake(Registered::class);
    }

    /** @test */
    public function it_listens_to_an_event()
    {
        Event::assertListening(Registered::class, SendWelcomeNotification::class);
    }

    /** @test */
    public function it_sends_a_welcome_notification()
    {
        $user = User::factory()->create();

        Notification::assertNothingSentTo($user);

        (new SendWelcomeNotification)->handle(new Registered($user));

        Notification::assertSentTo($user, function (Welcome $notification) use ($user) {
            return
                Hash::check($notification->token, $user->fresh()->activation_token) &&
                $notification->toMail($user)->actionUrl == config('app.frontend_url').'/activate/'.$notification->token.'?id='.$user->id;
        });
    }
}
