<?php

namespace Tests\Feature\V1\Authentication\Profile;

use Tests\Feature\FeatureTestCase;

abstract class AbstractProfileTestCase extends FeatureTestCase
{
    /**
     * Get the structure for a profile.
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
     * {@inheritdoc}
     */
    protected function getUrl(): string
    {
        return '/api/v1/profile';
    }
}
