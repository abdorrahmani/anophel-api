<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     *
     * @OA\Post(
     *      path="/api/v1/register",
     *      summary="Register a new user",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the registered user"),
     *                  @OA\Property(property="user", type="object", description="User details"),
     *          ),
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Error message describing validation failure"),
     *          ),
     *      ),
     *  )
     */
    public function register (RegisterRequest $request) {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'remember_token' => Str::random(10),
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $tokenExpiresAt = Carbon::parse($user->tokens()->first()->expires_at)->toDateTimeString();

        return $this->responseWithToken($token, $tokenExpiresAt, $user);
    }

    /**
     * @param LoginRequest $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     * @OA\Post(
     *      path="/api/v1/login",
     *      summary="Login a user",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *          ),
     *      ),
     *   @OA\Response(
     *          response=200,
     *          description="Login successful",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the authenticated user"),
     *              @OA\Property(property="token_expires_at", type="string", format="date-time", description="Expiration date of the access token"),
     *              @OA\Property(property="user", description="User details"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid credentials or user not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Error message"),
     *          ),
     *      ),
     *  )
     */
    public function login (LoginRequest $request) {
        $validatedData = $request->validated();

        $user = User::whereEmail($validatedData['email'])->first();

        if ($user && Hash::check($validatedData['password'], $user->password)) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $tokenExpiresAt = Carbon::parse($user->tokens()->first()->expires_at)->toDateTimeString();

            return $this->responseWithToken($token, $tokenExpiresAt, $user);
        }

        return response([
            'message' => $user ? 'Password mismatch' : 'User does not exist'
        ], 422);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Authentication"},
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have been successfully logged out!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         ),
     *     ),
     * )
     */
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/v1/user",
     *      summary="Get current authenticated user",
     *      tags={"Authentication"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Returns the current authenticated user",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", description="Authenticated user details"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Error message"),
     *          ),
     *      ),
     *  )
     *
     */
    public function getCurrentUser(): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            // User is authenticated
            return response()->json(['user' => $user], 200);
        }

        // No user is authenticated
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    /**
     * @param $token
     * @param $expire_at
     * @param $user
     * @return JsonResponse
     */
    public function responseWithToken($token, $expire_at, $user): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_expires_at' => $expire_at,
            'user' => $user
        ],200);
    }
}
