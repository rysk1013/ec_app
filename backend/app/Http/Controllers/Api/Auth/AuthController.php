<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\RegisterResource;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\AuthenticatedUser;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private AuthService $AuthService;

    public function __construct(
        AuthService $AuthService,
    ) {
        $this->AuthService = $AuthService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->AuthService->storeUser($request->all());

        return (new RegisterResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->all())) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        $token = $user->createToken('AccessToken')->plainTextToken;

        return (new LoginResource($user, $token))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function getAuthenticatedUser(Request $request)
    {
        return (new AuthenticatedUser($request->user()))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
