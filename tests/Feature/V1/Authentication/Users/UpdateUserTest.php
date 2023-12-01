<?php

namespace Tests\Feature\V1\Authentication\Users;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\Feature\Helpers\ValidationCase;
use Tests\Feature\Helpers\Verbs\Eloquent\TestsUpdate;

class UpdateUserTest extends AbstractUserTestCase
{
    use TestsUpdate;

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'name' => 'Sofie',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidationCases(): Collection
    {
        return collect([
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
