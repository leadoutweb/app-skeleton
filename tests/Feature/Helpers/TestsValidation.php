<?php

namespace Tests\Feature\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;

trait TestsValidation
{
    /** @test */
    public function can_not_access_the_endpoint_with_invalid_input()
    {
        $this->getValidationCases()->each(function (ValidationCase $case) {
            $response = $this->valid()->request($case->getOverrides());

            $response->assertUnprocessable();

            $response->assertJsonValidationErrorFor($case->getField());

            $this->tearDown();

            $this->setUp();
        });
    }

    /**
     * Get the validation cases for the endpoint.
     */
    protected function getValidationCases(): Collection
    {
        return collect();
    }

    /**
     * Add valid headers to the request.
     */
    abstract protected function valid(): static;

    /**
     * Send the request to the endpoint, optionally using the given parameters for the request.
     */
    abstract protected function request(array $parameters = []): TestResponse;
}
