<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OAuthTokenService
{
    private string $tokenEndpoint;
    private string $clientId;
    private string $clientSecret;
    private string $audience;

    public function __construct()
    {
        $this->tokenEndpoint = config('services.oauth.token_endpoint');
        $this->clientId = config('services.oauth.client_id');
        $this->clientSecret = config('services.oauth.client_secret');
        $this->audience = config('services.oauth.audience');
    }

    /**
     * Get an access token using client credentials flow
     */
    public function getAccessToken(): string
    {
        $cacheKey = 'oauth_access_token';
        
        // Check if we have a cached token
        $cachedToken = Cache::get($cacheKey);
        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            $response = Http::asForm()->post($this->tokenEndpoint, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'audience' => $this->audience,
            ]);

            if ($response->failed()) {
                Log::error('OAuth token request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new Exception('Failed to obtain access token');
            }

            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];
            $expiresIn = $tokenData['expires_in'] ?? 3600;

            // Cache the token for slightly less than its expiry time
            $cacheExpiry = max(1, $expiresIn - 300); // 5 minutes buffer
            Cache::put($cacheKey, $accessToken, $cacheExpiry);

            return $accessToken;
        } catch (Exception $e) {
            Log::error('OAuth token service error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Validate a Bearer token by introspecting it
     */
    public function validateToken(string $token): ?array
    {
        $cacheKey = "token_validation:" . md5($token);
        
        // Check cache first (short cache to avoid too many introspection calls)
        $cachedResult = Cache::get($cacheKey);
        if ($cachedResult !== null) {
            return $cachedResult;
        }

        try {
            // For JWT tokens, we can decode them locally
            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                return null;
            }

            // Decode the payload (base64url decode)
            $payload = json_decode(base64_decode(strtr($tokenParts[1], '-_', '+/')), true);
            
            if (!$payload) {
                return null;
            }

            // Validate basic JWT structure and claims
            $now = time();
            
            // Check expiration
            if (isset($payload['exp']) && $payload['exp'] < $now) {
                Cache::put($cacheKey, null, 300); // Cache negative result briefly
                return null;
            }

            // Check not before
            if (isset($payload['nbf']) && $payload['nbf'] > $now) {
                return null;
            }

            // Check audience
            if (isset($payload['aud'])) {
                $audiences = is_array($payload['aud']) ? $payload['aud'] : [$payload['aud']];
                if (!in_array($this->audience, $audiences)) {
                    Log::warning('Token audience mismatch', [
                        'expected' => $this->audience,
                        'received' => $payload['aud'],
                    ]);
                    Cache::put($cacheKey, null, 300);
                    return null;
                }
            }

            // Cache valid result for a short time
            Cache::put($cacheKey, $payload, 300); // 5 minutes
            
            return $payload;
        } catch (Exception $e) {
            Log::error('Token validation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Revoke a token (if the OAuth server supports it)
     */
    public function revokeToken(string $token): bool
    {
        try {
            // This would depend on your OAuth server's revocation endpoint
            // For now, we'll just remove it from cache
            $cacheKey = "token_validation:" . md5($token);
            Cache::forget($cacheKey);
            
            return true;
        } catch (Exception $e) {
            Log::error('Token revocation error', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}