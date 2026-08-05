<?php

namespace App\Http\Middleware;

use App\Models\Organisation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Authenticate requests using the X-API-Key header.
     * Binds the resolved Organisation to the request as `organisation`.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API key is required. Pass it in the X-API-Key header.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $organisation = Organisation::where('api_key', $apiKey)
            ->where('is_active', true)
            ->first();

        if (!$organisation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive API key.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Make the organisation available downstream via request()
        $request->merge(['_organisation' => $organisation]);
        $request->attributes->set('organisation', $organisation);

        return $next($request);
    }
}
