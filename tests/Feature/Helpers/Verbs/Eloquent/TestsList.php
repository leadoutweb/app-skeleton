<?php

namespace Tests\Feature\Helpers\Verbs\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait TestsList
{
    use TestsVerb;

    /**
     * The models that are accessed in the endpoint.
     */
    protected Collection $resources;

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->getResource();

        $this->resources = Collection::times(2, fn () => $this->getResource());
    }

    /** @test */
    public function can_list_the_resource()
    {
        $response = $this->valid()->request();

        $response->assertSuccessful();

        $response->assertJsonStructure($this->getPaginationStructure());

        $response->assertJsonStructure(['data' => ['*' => $this->getStructure()]]);
    }

    /** @test */
    public function can_filter_the_resource()
    {
        $response = $this
            ->valid()
            ->query(['filter['.$this->getFilterProperty().']' => $this->resources->first()->getAttribute($this->getFilterProperty())])
            ->request();

        $response->assertSuccessful();

        $response->assertJsonFragment(['id' => $this->resources->first()->id]);

        $response = $this
            ->valid()
            ->query(['filter['.$this->getFilterProperty().']' => Str::uuid()->toString()])
            ->request();

        $response->assertSuccessful();

        $response->assertJsonMissing(['id' => $this->resources->first()->id]);
    }

    /** @test */
    public function can_sort_the_resource()
    {
        $ascending = $this
            ->valid()
            ->query(['sort' => $this->getSortProperty()])
            ->request();

        $this->assertGreaterThan(1, count($ascending->json('data')));

        $this->assertEquals(
            collect($ascending->json('data'))
                ->pluck('attributes')
                ->pluck($this->getSortProperty())
                ->sort()
                ->values(),
            collect($ascending->json('data'))->pluck('attributes')->pluck($this->getSortProperty()),
        );

        $descending = $this
            ->valid()
            ->query(['sort' => '-'.$this->getSortProperty()])
            ->request();

        $this->assertGreaterThan(1, count($descending->json('data')));

        $this->assertEquals(
            collect($descending->json('data'))
                ->pluck('attributes')
                ->pluck($this->getSortProperty())
                ->sortDesc()
                ->values(),
            collect($descending->json('data'))->pluck('attributes')->pluck($this->getSortProperty()),
        );
    }

    /** @test */
    public function can_set_the_page_size()
    {
        $response = $this->valid()->request();

        $this->assertGreaterThan(1, count($response->json('data')));

        $response = $this->valid()->query(['page[size]' => 1])->request();

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function can_set_the_page_number()
    {
        $response = $this->valid()->request();

        $this->assertGreaterThan(1, count($response->json('data')));

        $this->assertEquals(
            $response->json('data.0.id'),
            $this->valid()->query(['page[size]' => 1, 'page[number]' => 1])->request()->json('data.0.id')
        );

        $this->assertEquals(
            $response->json('data.1.id'),
            $this->valid()->query(['page[size]' => 1, 'page[number]' => 2])->request()->json('data.0.id')
        );
    }

    /**
     * Get the HTTP method to use when accessing the endpoint.
     */
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * Get the URL for the endpoint.
     */
    protected function getUrl(): string
    {
        return $this->getBaseUrl();
    }

    /**
     * Get the pagination structure.
     */
    protected function getPaginationStructure(): array
    {
        return [
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ];
    }

    /**
     * Get the name of the property to filter on.
     */
    protected function getFilterProperty(): string
    {
        return 'id';
    }

    /**
     * Get the name of the property to sort on.
     */
    protected function getSortProperty(): string
    {
        return 'id';
    }

    /**
     * Get the JSON structure of the resource.
     */
    abstract protected function getStructure(): array;

    /**
     * Get the resource used for testing.
     */
    abstract protected function getResource(): Model;
}
