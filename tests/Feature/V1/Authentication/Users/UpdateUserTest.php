<?php

namespace Tests\Feature\V1\Authentication\Users;

use App\Authentication\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\Feature\Helpers\ValidationCase;
use Tests\Feature\Helpers\Verbs\Eloquent\TestsUpdate;

class UpdateUserTest extends AbstractUserTestCase
{
    use TestsUpdate;

    /** @test */
    public function can_update_a_user_without_changing_the_email()
    {
        $response = $this->valid()->request([
            'email' => $this->resource->email,
        ]);

        $response->assertSuccessful();
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'email' => 'sofie@example.com',
            'name' => 'Sofie',
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

    /**
     * {@inheritdoc}
     */
    protected function assertResourceUpdated(): void
    {
        $this->assertDatabaseHas('users', [
            'id' => $this->resource->id,
            ...$this->parameters,
        ]);
    }
}
