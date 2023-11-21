<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

use Illuminate\Database\Eloquent\Model;

trait TestsSingle
{
    use TestsVerb;

    /**
     * The model that is accessed in the endpoint.
     */
    protected Model $resource;

    /**
     * {@inheritdoc}
     */
    protected function prepare(): void
    {
        $this->resource = $this->getResource();
    }

    /** @test */
    public function can_not_access_the_endpoint_for_a_resource_that_does_not_exist()
    {
        $this->resource->delete();

        $response = $this->usesAuthentication() ? $this->valid()->request() : $this->request();

        $response->assertNotFound();
    }

    /**
     * Get the URL for the endpoint.
     */
    protected function getUrl(): string
    {
        return $this->getBaseUrl().'/'.$this->resource->id;
    }

    /**
     * Get the resource used for testing.
     */
    abstract protected function getResource(): Model;
}
