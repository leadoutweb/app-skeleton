<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

use Illuminate\Testing\TestResponse;
use Tests\Feature\Helpers\TestsValidation;

trait TestsUpdate
{
    use TestsSingle, TestsValidation;

    /** @test */
    public function can_update_the_resource()
    {
        $response = $this->usesAuthentication() ? $this->valid()->request() : $this->request();

        $this->assertUpdated($response);
    }

    /**
     * Assert that the resource was updated.
     */
    protected function assertUpdated(TestResponse $response): void
    {
        $this->assertUpdatedResponse($response);

        $this->assertResourceUpdated();
    }

    /**
     * Make assertions on the response from the endpoint.
     */
    protected function assertUpdatedResponse(TestResponse $response): void
    {
        $response->assertSuccessful();

        $response->assertJsonStructure(['data' => $this->getStructure()]);
    }

    /**
     * Assert that a resource was updated.
     */
    abstract protected function assertResourceUpdated(): void;

    /**
     * Get the HTTP method to use when accessing the endpoint.
     */
    protected function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * Get the JSON structure of the resource.
     */
    abstract protected function getStructure(): array;
}
