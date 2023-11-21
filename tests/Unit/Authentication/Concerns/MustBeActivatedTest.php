<?php

namespace Tests\Unit\Authentication\Concerns;

use App\Authentication\Exceptions\InvalidActivationTokenException;
use App\Authentication\Exceptions\UserAlreadyActivatedException;
use App\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class MustBeActivatedTest extends TestCase
{
    /** @test */
    public function can_create_an_activation_token_for_the_model()
    {
        $user = User::factory()->create();

        $token = $user->createActivationToken();

        $this->assertTrue(Hash::check($token, $user->fresh()->activation_token));
    }

    /** @test */
    public function can_activate_a_model()
    {
        $user = User::factory()->create();

        $user->activate($user->createActivationToken(), 'Aa8!aaaa');

        $this->assertTrue(Hash::check('Aa8!aaaa', $user->fresh()->password));

        $this->assertNotNull($user->fresh()->activated_at);
    }

    /** @test */
    public function can_not_activate_a_model_that_has_already_been_activated()
    {
        $user = User::factory()->create();

        $token = $user->createActivationToken();

        $user->forceActivate();

        $this->expectException(UserAlreadyActivatedException::class);

        $user->activate($token, 'Aa8!aaaa');
    }

    /** @test */
    public function can_not_activate_a_model_with_an_invalid_activation_token()
    {
        $user = User::factory()->create();

        $user->createActivationToken();

        $this->expectException(InvalidActivationTokenException::class);

        $user->activate(Str::uuid()->toString(), 'Aa8!aaaa');
    }
}
