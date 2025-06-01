<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterFailedException extends Exception
{
    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
