<?php

namespace App\Providers;

use App\Authentication\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::$createUrlCallback = function (User $user, string $token) {
            return config('app.frontend_url').'/password/reset/'.$token.'?email='.$user->email;
        };
    }
}
