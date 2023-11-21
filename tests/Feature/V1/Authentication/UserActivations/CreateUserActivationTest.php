<?php

namespace Tests\Feature\V1\Authentication\UserActivations;

use App\Authentication\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;
use Tests\Feature\Helpers\TestsValidation;
use Tests\Feature\Helpers\ValidationCase;

class CreateUserActivationTest extends FeatureTestCase
{
    use TestsValidation;

    /**
     * The user to activate.
     */
    private User $user;

    /** @test */
    public function can_activate_a_user()
    {
        $response = $this->request();

        $response->assertSuccessful();

        $this->assertTrue(Hash::check('Aa8!aaaa', $this->user->fresh()->password));

        $this->assertNotNull($this->user->fresh()->activated_at);
    }

    /** @test */
    public function can_not_activate_a_user_that_has_been_activated()
    {
        $this->user->forceActivate();

        $response = $this->request();

        $this->assertDomainException($response, 'USER_ALREADY_ACTIVATED');
    }

    /** @test */
    public function can_not_activate_a_user_with_an_invalid_activation_token()
    {
        $response = $this->request([
            'token' => Str::uuid()->toString(),
        ]);

        $this->assertDomainException($response, 'INVALID_ACTIVATION_TOKEN');
    }

    /** @test */
    public function can_not_activate_a_user_that_does_not_exist()
    {
        $this->user->delete();

        $response = $this->request();

        $response->assertNotFound();
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
        return '/api/v1/users/'.$this->user->id.'/activations';
    }

    /**
     * {@inheritdoc}
     */
    protected function prepare(): void
    {
        $this->user = User::factory()->create();
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'token' => $this->user->createActivationToken(),
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
