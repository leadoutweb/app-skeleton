<?php

namespace Tests\Feature\V1\Authentication\Activity;

use Database\Factories\Authentication\ActivityFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Feature\FeatureTestCase;

abstract class AbstractActivityTestCase extends FeatureTestCase
{
    /**
     * Get the structure for an activity.
     */
    protected function getStructure(): array
    {
        return [
            'id',
            'type',

            'attributes' => [
                'description',
                'event',
                'properties',
                'subject_name',
                'causer_name',

                'subject_id',
                'subject_type',
                'causer_id',
                'causer_type',

                'created_at',
                'updated_at',
            ],
        ];
    }

    /**
     * Get the resource being accessed in the endpoint.
     */
    protected function getResource(): Model
    {
        return ActivityFactory::new()->create();
    }

    /**
     * Get the base URL to query when manipulating the resource.
     */
    protected function getBaseUrl(): string
    {
        return '/api/v1/activity';
    }
}
