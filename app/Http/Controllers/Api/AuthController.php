<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};

use App\Services\Auth\AuthService;
use App\Http\Requests\Auth\{LoginRequest, UpdateProfileRequest};
use App\Http\Resources\User\UserResource;

class AuthController extends Controller
{

    public function user(Request $request)
    {
        return $request->user();
    }

    public function login(AuthService $authService, LoginRequest $request): JsonResponse
    {
        $token = $authService->login($request->all());

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function profile(UpdateProfileRequest $request): UserResource
    {
        $user = $request->user();
        $user->update($request->all());

        return new UserResource($user);
    }

}
