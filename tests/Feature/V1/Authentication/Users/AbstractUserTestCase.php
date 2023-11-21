<?php

namespace Tests\Feature\V1\Authentication\Users;

use App\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\Feature\FeatureTestCase;

abstract class AbstractUserTestCase extends FeatureTestCase
{
    /**
     * Get the structure for a user.
     */
    protected function getStructure(): array
    {
        return [
            'id',
            'type',

            'attributes' => [
                'email',
                'name',

                'created_at',
                'updated_at',
            ],
        ];
    }

    /**
     * Get the base URL to query when manipulating the resource.
     */
    protected function getBaseUrl(): string
    {
        return '/api/v1/users';
    }

    /**
     * Get the resource being accessed in the endpoint.
     */
    protected function getResource(): Model
    {
        return User::factory()->create();
    }
}
