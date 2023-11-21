<?php

namespace Tests\Feature\V1\Authentication\PasswordResetLinks;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestCase;
use Tests\Feature\Helpers\TestsValidation;
use Tests\Feature\Helpers\ValidationCase;

class CreatePasswordResetLinkTest extends FeatureTestCase
{
    use TestsValidation;

    /** @test */
    public function can_send_a_password_reset_link()
    {
        $response = $this->request();

        $response->assertSuccessful();

        Notification::assertSentTo($this->authenticatedUser, function (ResetPassword $notification) {
            return $notification->toMail($this->authenticatedUser)->actionUrl ==
                config('app.frontend_url').'/password/reset/'.$notification->token.'?email='.$this->authenticatedUser->email;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUrl(): string
    {
        return '/api/v1/password-reset-links';
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'email' => $this->authenticatedUser->email,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidationCases(): Collection
    {
        return collect([
            ValidationCase::make(['email' => null]),
            ValidationCase::make(['email' => 'not-an-email']),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function usesAuthentication(): bool
    {
        return false;
    }
}
