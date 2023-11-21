<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Authentication\Models\User;
use App\Http\Controllers\V1\Controller;
use App\Http\Requests\Authentication\Users\StoreUserRequest;
use App\Http\Requests\Authentication\Users\UpdateUserRequest;
use App\Http\Resources\Authentication\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Show a list of users.
     */
    public function index(): AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['id'])
            ->apiPaginate();

        return UserResource::collection($users);
    }

    /**
     * Store a new user.
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->validated());

        return UserResource::make($user);
    }

    /**
     * Show the details of the given user.
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    /**
     * Update the user.
     */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return UserResource::make($user);
    }

    /**
     * Delete the given user.
     */
    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
}
