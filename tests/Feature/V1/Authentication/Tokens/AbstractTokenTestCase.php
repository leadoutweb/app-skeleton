<?php

namespace Tests\Feature\V1\Authentication\Tokens;

use Tests\Feature\FeatureTestCase;

abstract class AbstractTokenTestCase extends FeatureTestCase
{
    /**
     * Get the structure for a token.
     */
    protected function getStructure(): array
    {
        return [
            'id',
            'type',

            'attributes' => [
                'value',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getUrl(): string
    {
        return '/api/v1/tokens';
    }
}
