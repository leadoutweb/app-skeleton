<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

use Illuminate\Testing\TestResponse;

trait TestsVerb
{
    /**
     * Add valid headers to the request.
     */
    abstract protected function valid(): static;

    /**
     * Use the given query parameters for the request.
     */
    abstract protected function query(array $query): static;

    /**
     * Send the request to the endpoint, optionally using the given overrides for the request.
     */
    abstract protected function request(array $overrides = []): TestResponse;

    /**
     * Get the base URL for the resource.
     */
    abstract protected function getBaseUrl(): string;

    /**
     * Determine if the endpoint uses authentication.
     */
    abstract protected function usesAuthentication(): bool;
}
