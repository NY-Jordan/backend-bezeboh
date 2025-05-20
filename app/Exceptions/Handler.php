<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Http\Request;

class Handler
{
    public static function render(Throwable $e, Request $request)
    {

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticate',
            ], 401);
        }

        if ($e instanceof HttpException) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
