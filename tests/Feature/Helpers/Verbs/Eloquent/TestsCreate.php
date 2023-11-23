<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\Feature\Helpers\TestsValidation;

trait TestsCreate
{
    use TestsValidation, TestsVerb;

    /** @test */
    public function can_create_a_resource()
    {
        $response = $this->usesAuthentication() ? $this->valid()->request() : $this->request();
dd(DB::table('activity_log')->get());
        $this->assertCreated($response);
    }

    /**
     * Assert that the resource was created.
     */
    protected function assertCreated(TestResponse $response): void
    {
        $this->assertCreatedResponse($response);

        $this->assertResourceCreated();
    }

    /**
     * Make assertions on the response from the endpoint.
     */
    protected function assertCreatedResponse(TestResponse $response): void
    {
        $response->assertSuccessful();

        $response->assertJsonStructure(['data' => $this->getStructure()]);
    }

    /**
     * Assert that a resource was created.
     */
    abstract protected function assertResourceCreated(): void;

    /**
     * Get the HTTP method to use when accessing the endpoint.
     */
    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * Get the URL for the endpoint.
     */
    protected function getUrl(): string
    {
        return $this->getBaseUrl();
    }

    /**
     * Get the JSON structure of the resource.
     */
    abstract protected function getStructure(): array;
}
