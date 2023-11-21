<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

use Illuminate\Testing\TestResponse;

trait TestsDelete
{
    use TestsSingle;

    /** @test */
    public function can_delete_the_resource()
    {
        $response = $this->usesAuthentication() ? $this->valid()->request() : $this->request();

        $this->assertDeleted($response);
    }

    /**
     * Assert that the resource was deleted.
     */
    protected function assertDeleted(TestResponse $response): void
    {
        $response->assertNoContent();

        $this->assertResourceDeleted();
    }

    /**
     * Assert that a resource was created.
     */
    protected function assertResourceDeleted(): void
    {
        $this->assertModelMissing($this->resource);
    }

    /**
     * Get the HTTP method to use when accessing the endpoint.
     */
    protected function getMethod(): string
    {
        return 'DELETE';
    }
}
