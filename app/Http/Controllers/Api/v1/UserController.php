<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class UserController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @OA\Get(
     *      path="/api/v1/users",
     *      summary="Get a list of users",
     *      tags={"Users"},
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Invalid request")
     *  )
     */

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection(User::with('userInfo')->latest()->paginate(15));
    }


    /**
     * @param User $user
     *
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     * @OA\Get(
     *       path="/api/v1/users/{id}",
     *       summary="Get a user profile",
     *       tags={"Users"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User's id",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *
     *   )
     */
    public function profile(User $user)
    {
        return response(new UserResource($user->load('userInfo')),200);
    }
}
