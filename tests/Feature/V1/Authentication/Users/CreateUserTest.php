<?php

namespace Tests\Feature\V1\Authentication\Users;

use App\Authentication\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\Feature\Helpers\ValidationCase;
use Tests\Feature\Helpers\Verbs\Eloquent\TestsCreate;

class CreateUserTest extends AbstractUserTestCase
{
    use TestsCreate;

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'email' => 'jonas@example.com',
            'name' => 'Jonas',
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
    protected function assertResourceCreated(): void
    {
        $this->assertDatabaseHas('users', $this->parameters);
    }
}
