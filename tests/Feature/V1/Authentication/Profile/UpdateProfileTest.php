<?php

namespace Tests\Feature\V1\Authentication\Profile;

use App\Authentication\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\Feature\Helpers\TestsValidation;
use Tests\Feature\Helpers\ValidationCase;

class UpdateProfileTest extends AbstractProfileTestCase
{
    use TestsValidation;

    /** @test */
    public function can_update_the_profile_of_the_authenticated_user()
    {
        $response = $this->usesAuthentication() ? $this->valid()->request() : $this->request();

        $response->assertSuccessful();

        $response->assertJsonStructure(['data' => $this->getStructure()]);

        $this->assertDatabaseHas('users', [
            'id' => $this->authenticatedUser->id,
            ...$this->parameters,
        ]);
    }

    /** @test */
    public function can_update_the_profile_of_the_authenticated_user_without_changing_the_email()
    {
        $response = $this->valid()->request([
            'email' => $this->authenticatedUser->email,
        ]);

        $response->assertSuccessful();
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'email' => 'henrik@example.com',
            'name' => 'Henrik',
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
            ValidationCase::make(fn () => ['email' => User::factory()->create()->email]),
            ValidationCase::make(['email' => Str::repeat('A', 101)]),
            ValidationCase::make(['name' => null]),
            ValidationCase::make(['name' => ['not-a-string']]),
            ValidationCase::make(['name' => Str::repeat('A', 101)]),
        ]);
    }
}
