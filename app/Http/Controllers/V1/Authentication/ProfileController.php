<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\Authentication\Profiles\UpdateProfileRequest;
use App\Http\Resources\Authentication\ProfileResource;

class ProfileController extends Controller
{
    /**
     * Show the profile of the authenticated user.
     */
    public function show(): ProfileResource
    {
        return ProfileResource::make(auth()->user());
    }

    /**
     * Update the profile of the authenticated user.
     */
    public function update(UpdateProfileRequest $request): ProfileResource
    {
        auth()->user()->update($request->validated());

        return ProfileResource::make(auth()->user());
    }
}
