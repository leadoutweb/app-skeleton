<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Authentication\Models\User;
use App\Http\Controllers\V1\Controller;
use App\Http\Requests\Authentication\UserActivations\StoreUserActivationRequest;
use Illuminate\Http\Response;

class UserActivationController extends Controller
{
    /**
     * Store an activation for the given user.
     */
    public function store(StoreUserActivationRequest $request, User $user): Response
    {
        $user->activate($request->validated('token'), $request->validated('password'));

        return response()->noContent();
    }
}
