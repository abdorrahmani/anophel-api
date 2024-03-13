<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection(User::with('userInfo')->latest()->paginate(15));
    }

    public function profile(User $user): UserResource
    {
        return new UserResource($user->load('userInfo'));
    }
}
