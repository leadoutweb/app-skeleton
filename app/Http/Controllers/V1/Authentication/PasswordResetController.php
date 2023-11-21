<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Authentication\Exceptions\InvalidPasswordResetEmailException;
use App\Authentication\Exceptions\InvalidPasswordResetTokenException;
use App\Authentication\Models\User;
use App\Http\Controllers\V1\Controller;
use App\Http\Requests\Authentication\PasswordResets\StorePasswordResetRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * Reset the password for a user.
     */
    public function store(StorePasswordResetRequest $request): Response
    {
        $status = Password::reset($request->validated(), function (User $user, string $password) {
            $user->update(compact('password'));
        });

        return match ($status) {
            Password::INVALID_TOKEN => throw new InvalidPasswordResetTokenException,
            Password::INVALID_USER => throw new InvalidPasswordResetEmailException,
            default => response()->noContent()
        };
    }
}
