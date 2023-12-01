<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Authentication\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Authentication\Tokens\StoreTokenRequest;
use App\Http\Resources\Authentication\TokenResource;
use Illuminate\Http\Response;

class TokenController
{
    /**
     * Create a token for the given credentials.
     */
    public function store(StoreTokenRequest $request): TokenResource
    {
        if ($token = auth()->guard()->attempt($request->validated())) {
            return TokenResource::make($token);
        }

        throw new InvalidCredentialsException;
    }

    /**
     * Delete the token in the request.
     */
    public function destroy(): Response
    {
        auth()->guard()->getTokenManager()->invalidate(request());

        return response()->noContent();
    }
}
