<?php

namespace Tests\Feature\V1\Authentication\PasswordResets;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;
use Tests\Feature\Helpers\TestsValidation;
use Tests\Feature\Helpers\ValidationCase;

class CreatePasswordResetTest extends FeatureTestCase
{
    use TestsValidation;

    /** @test */
    public function can_reset_a_password()
    {
        $response = $this->request();

        $response->assertSuccessful();

        $this->assertTrue(Hash::check('Aa8!aaaa', $this->authenticatedUser->fresh()->password));
    }

    /** @test */
    public function can_not_reset_a_password_with_an_invalid_token()
    {
        $response = $this->request(['token' => 'ABC']);

        $this->assertDomainException($response, 'INVALID_PASSWORD_RESET_TOKEN');
    }

    /** @test */
    public function can_not_reset_a_password_with_an_invalid_email()
    {
        $response = $this->request(['email' => 'henrik+'.Str::uuid()->toString().'@example.com']);

        $this->assertDomainException($response, 'INVALID_PASSWORD_RESET_EMAIL');
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
        return '/api/v1/password-resets';
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'token' => Password::createToken($this->authenticatedUser),
            'email' => $this->authenticatedUser->email,
            ...$this->confirmed('Aa8!aaaa'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidationCases(): Collection
    {
        return collect([
            ValidationCase::make(['token' => null]),
            ValidationCase::make(['token' => ['not-a-string']]),
            ValidationCase::make(['password' => null]),
            ValidationCase::make(['password_confirmation' => 'not-secret'], 'password'),
            ValidationCase::make($this->confirmed('Aa8!aaa')),
            ValidationCase::make($this->confirmed('888!8888')),
            ValidationCase::make($this->confirmed('aa8!aaaa')),
            ValidationCase::make($this->confirmed('Aaa!aaaa')),
            ValidationCase::make($this->confirmed('Aa8aaaaa')),
        ]);
    }

    /**
     * Get the parameters for a confirmed password.
     */
    private function confirmed(string $value): array
    {
        return [
            'password' => $value,
            'password_confirmation' => $value,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function usesAuthentication(): bool
    {
        return false;
    }
}
