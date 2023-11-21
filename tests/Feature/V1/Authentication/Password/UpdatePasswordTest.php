<?php

namespace Tests\Feature\V1\Authentication\Password;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\FeatureTestCase;
use Tests\Feature\Helpers\TestsValidation;
use Tests\Feature\Helpers\ValidationCase;

class UpdatePasswordTest extends FeatureTestCase
{
    use TestsValidation;

    /** @test */
    public function can_update_the_password_of_the_authenticated_user()
    {
        $response = $this->valid()->request();

        $response->assertNoContent();

        $this->assertTrue(Hash::check('Aa8!aaaa', $this->authenticatedUser->fresh()->password));
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return $this->confirmed('Aa8!aaaa');
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidationCases(): Collection
    {
        return collect([
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
    protected function getUrl(): string
    {
        return '/api/v1/password';
    }

    /**
     * {@inheritDoc}
     */
    protected function getMethod(): string
    {
        return 'PUT';
    }
}
