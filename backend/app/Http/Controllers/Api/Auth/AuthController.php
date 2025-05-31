<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\RegisterResource;
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
}
