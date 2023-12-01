<?php

namespace App\Authentication\Concerns;

use App\Authentication\Exceptions\InvalidActivationTokenException;
use App\Authentication\Exceptions\UserAlreadyActivatedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

trait MustBeActivated
{
    /**
     * Create an activation token for the user.
     */
    public function createActivationToken(): string
    {
        $this->activation_token = $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        $this->saveQuietly();

        return $token;
    }

    /**
     * Activate the user.
     */
    public function activate(string $token, string $password): void
    {
        $this->guardAlreadyActivated();

        $this->guardInvalidActivationToken($token);

        $this->password = $password;

        $this->activated_at = Carbon::now();

        $this->saveQuietly();

        activity()->performedOn($this)->event('activated')->log('activated');
    }

    /**
     * Guard that the user is not already activated.
     */
    private function guardAlreadyActivated(): void
    {
        if ($this->activated_at) {
            throw new UserAlreadyActivatedException;
        }
    }

    /**
     * Guard that the activation token is valid.
     */
    private function guardInvalidActivationToken(string $token): void
    {
        try {
            if (! Hash::check($token, $this->activation_token)) {
                throw new InvalidActivationTokenException;
            }
        } catch (RuntimeException) {
            throw new InvalidActivationTokenException;
        }
    }

    /**
     * Activate the user without checking a token and even if it is already activated.
     */
    public function forceActivate(): void
    {
        $this->password = Str::random(40);

        $this->activated_at = Carbon::now();

        $this->save();
    }
}
