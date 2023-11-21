<?php

namespace Tests\Feature\V1\Authentication\Profile;

class ShowProfileTest extends AbstractProfileTestCase
{
    /** @test */
    public function can_show_the_profile_of_the_authenticated_user()
    {
        $response = $this->valid()->request();

        $response->assertSuccessful();

        $this->assertEquals($this->authenticatedUser->id, $response->json('data.id'));

        $response->assertJsonStructure(['data' => $this->getStructure()]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod(): string
    {
        return 'GET';
    }
}
