<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    public function handle(Request $request, Closure $next): Response
    {

        $providedKey = $request->header('X-API-KEY');
        $validKey = config('services.api.key');

        if (!$providedKey || $providedKey !== $validKey) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
