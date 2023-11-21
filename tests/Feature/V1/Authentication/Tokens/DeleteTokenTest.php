<?php

namespace Tests\Feature\V1\Authentication\Tokens;

class DeleteTokenTest extends AbstractTokenTestCase
{
    /** @test */
    public function can_invalidate_a_token()
    {
        $token = auth()->getTokenManager()->issue($this->authenticatedUser)->getValue();

        $this->assertTokenIsValid($token);

        $response = $this->withToken($token)->request();

        $response->assertSuccessful();

        $this->assertTokenIsInvalid($token);
    }

    /**
     * Assert that the given token is valid.
     */
    private function assertTokenIsValid(string $token): void
    {
        auth()->forgetUser();

        $this->withToken($token)->getJson('/api/v1/profile')->assertSuccessful();
    }

    /**
     * Assert that the given token is invalid.
     */
    private function assertTokenIsInvalid(string $token): void
    {
        auth()->forgetUser();

        $this->withToken($token)->getJson('/api/v1/profile')->assertUnauthorized();
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod(): string
    {
        return 'DELETE';
    }
}
