<?php

namespace App\Http\Middleware;

use App\Services\OAuthTokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseContract;

class ValidateOAuthToken
{
    private OAuthTokenService $oauthService;

    public function __construct(OAuthTokenService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): ResponseContract
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Bearer token required'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = substr($authHeader, 7); // Remove "Bearer " prefix
        
        if (empty($token)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid token format'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $tokenData = $this->oauthService->validateToken($token);
        
        if (!$tokenData) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid or expired token'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Add token data to request for use in controllers
        $request->merge(['token_data' => $tokenData]);
        
        // You can also set the authenticated user context if needed
        // For example, if you have a way to map token claims to users
        
        return $next($request);
    }
}