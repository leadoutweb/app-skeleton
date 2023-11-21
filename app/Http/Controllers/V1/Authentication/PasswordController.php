<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\Authentication\Passwords\UpdatePasswordRequest;
use Illuminate\Http\Response;

class PasswordController extends Controller
{
    /**
     * Update the password of the authenticated user.
     */
    public function update(UpdatePasswordRequest $request): Response
    {
        auth()->user()->update($request->validated());

        return response()->noContent();
    }
}
