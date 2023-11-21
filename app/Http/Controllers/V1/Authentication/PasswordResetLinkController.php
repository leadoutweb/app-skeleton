<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\Authentication\PasswordResetLinks\StorePasswordResetLinkRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Send a password reset link.
     */
    public function store(StorePasswordResetLinkRequest $request): Response
    {
        Password::sendResetLink($request->validated());

        return response()->noContent();
    }
}
