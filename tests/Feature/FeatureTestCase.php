<?php

namespace Tests\Feature;

use App\Authentication\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    /**
     * The authenticated user.
     */
    protected User $authenticatedUser;

    /**
     * The parameters to send with the request.
     */
    protected array $parameters = [];

    /**
     * Any query parameters to send with the request.
     */
    protected array $query = [];

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUser = $this->getAuthenticatedUser();

        $this->prepare();

        $this->parameters = $this->getParameters();
    }

    /** @test */
    public function can_not_access_the_endpoint_without_a_token()
    {
        if (! $this->usesAuthentication()) {
            $this->markTestSkipped('The endpoint does not use authentication.');
        }

        $response = $this->request();

        $response->assertUnauthorized();
    }

    /**
     * Get the HTTP method to use when accessing the endpoint.
     */
    abstract protected function getMethod(): string;

    /**
     * Get the URL for the endpoint.
     */
    abstract protected function getUrl(): string;

    /**
     * Send the request to the endpoint, optionally using the given overrides for the request.
     */
    protected function request(array $overrides = []): TestResponse
    {
        return $this->json(
            $this->getMethod(),
            $this->getUrl().(empty($this->query) ? '' : '?'.http_build_query($this->query)),
            Arr::undot([...$this->parameters, ...$overrides])
        );
    }

    /**
     * Add valid headers to the request.
     */
    protected function valid(): static
    {
        return $this->withUserToken();
    }

    /**
     * Add a bearer token for the given user to the request.
     */
    protected function withUserToken(Authenticatable $user = null): static
    {
        return $this->withToken(
            auth()
                ->getTokenManager()
                ->issue($user ?? $this->authenticatedUser)
                ->getValue()
        );
    }

    /**
     * Use the given query parameters for the request.
     */
    protected function query(array $query): static
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Prepare the test case by creating the models that are required for the tests etc.
     */
    protected function prepare(): void
    {
        //
    }

    /**
     * Get parameters for accessing the endpoint.
     */
    protected function getParameters(): array
    {
        return [];
    }

    /**
     * Assert that the given response is a domain exception.
     */
    protected function assertDomainException(TestResponse $response, string $error, array $data = []): void
    {
        $response->assertBadRequest();

        $this->assertEquals($error, $response->json('error'));

        $this->assertEquals($data, $response->json('data'));
    }

    /**
     * Determine if the endpoint uses authentication.
     */
    protected function usesAuthentication(): bool
    {
        return true;
    }

    /**
     * Get the authenticated user.
     */
    protected function getAuthenticatedUser(): User
    {
        return User::factory()->create([
            'email' => 'henrik@example.com',
            'password' => 'hidden',
        ]);
    }
}
