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
