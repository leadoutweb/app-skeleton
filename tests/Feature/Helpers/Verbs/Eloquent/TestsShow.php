<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

trait TestsShow
{
    use TestsSingle;

    /** @test */
    public function can_show_the_resource()
    {
        $response = $this->usesAuthentication() ? $this->valid()->request() : $this->request();

        $response->assertSuccessful();

        $response->assertJsonStructure(['data' => $this->getStructure()]);
    }

    /**
     * Get the HTTP method to use when accessing the endpoint.
     */
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * Get the JSON structure of the resource.
     */
    abstract protected function getStructure(): array;
}
