<?php

namespace App\Authentication\Notifications;

use App\Authentication\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class Welcome extends Notification
{
    /**
     * The password reset token.
     */
    public string $token;

    /**
     * Create a notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Welcome'))
            ->line(Lang::get('You are receiving this email because an account has been created for you.'))
            ->line(Lang::get('The account may be activated by following the link below.'))
            ->action(Lang::get('Activate Account'), $this->getUrl($notifiable))
            ->line(Lang::get('You can select a password when activating your account.'));
    }

    /**
     * Get the URL for selecting a password for the given user.
     */
    private function getUrl(User $notifiable): string
    {
        return config('app.frontend_url').'/activate/'.$this->token.'?id='.$notifiable->id;
    }
}
