<?php

namespace Tests\Feature\V1\Authentication\Tokens;

use Illuminate\Support\Collection;
use Leadout\JWT\Entities\Token;
use Tests\Feature\Helpers\TestsValidation;
use Tests\Feature\Helpers\ValidationCase;

class CreateTokenTest extends AbstractTokenTestCase
{
    use TestsValidation;

    /** @test */
    public function can_create_a_token()
    {
        $response = $this->valid()->request();

        $response->assertSuccessful();

        $response->assertJsonStructure(['data' => $this->getStructure()]);

        $this->assertEquals(
            $this->authenticatedUser->id,
            auth()
                ->guard()
                ->getTokenManager()
                ->getTokenProvider()
                ->decode(new Token($response->json('data.attributes.value')))
                ->claims()
                ->sub()
        );
    }

    /** @test */
    public function can_not_create_a_token_with_invalid_credentials()
    {
        $response = $this->valid()->request(['password' => 'not-hidden']);

        $this->assertDomainException($response, 'INVALID_CREDENTIALS');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    protected function getParameters(): array
    {
        return [
            'email' => 'henrik@example.com',
            'password' => 'hidden',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidationCases(): Collection
    {
        return collect([
            ValidationCase::make(['email' => null]),
            ValidationCase::make(['email' => ['not-a-string']]),
            ValidationCase::make(['password' => null]),
            ValidationCase::make(['password' => ['not-a-string']]),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function usesAuthentication(): bool
    {
        return false;
    }
}
