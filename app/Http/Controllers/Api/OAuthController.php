<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="OAuth",
 *     description="OAuth 2.0 Client Credentials Flow for API authentication. Each registered application has an associated OAuth client for API access."
 * )
 */
class OAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/oauth/token",
     *     tags={"OAuth"},
     *     summary="Get OAuth 2.0 access token",
     *     description="Exchange client credentials for an access token using OAuth 2.0 Client Credentials flow",
     *     operationId="getOAuthToken",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"grant_type", "client_id", "client_secret"},
     *                 @OA\Property(
     *                     property="grant_type",
     *                     type="string",
     *                     enum={"client_credentials"},
     *                     description="OAuth 2.0 grant type"
     *                 ),
     *                 @OA\Property(
     *                     property="client_id",
     *                     type="string",
     *                     description="OAuth client ID"
     *                 ),
     *                 @OA\Property(
     *                     property="client_secret",
     *                     type="string",
     *                     description="OAuth client secret"
     *                 ),
     *                 @OA\Property(
     *                     property="scope",
     *                     type="string",
     *                     description="Optional scope (defaults to all available scopes)",
     *                     example="read write"
     *                 )
     *             )
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"grant_type", "client_id", "client_secret"},
     *                 @OA\Property(
     *                     property="grant_type",
     *                     type="string",
     *                     enum={"client_credentials"},
     *                     description="OAuth 2.0 grant type"
     *                 ),
     *                 @OA\Property(
     *                     property="client_id",
     *                     type="string",
     *                     description="OAuth client ID"
     *                 ),
     *                 @OA\Property(
     *                     property="client_secret",
     *                     type="string",
     *                     description="OAuth client secret"
     *                 ),
     *                 @OA\Property(
     *                     property="scope",
     *                     type="string",
     *                     description="Optional scope (defaults to all available scopes)",
     *                     example="read write"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Access token issued successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", description="Bearer access token"),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", description="Token expiration time in seconds"),
     *             @OA\Property(property="scope", type="string", description="Granted scopes")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="invalid_request"),
     *             @OA\Property(property="error_description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid client credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="invalid_client"),
     *             @OA\Property(property="error_description", type="string")
     *         )
     *     )
     * )
     */
    public function token(Request $request): JsonResponse
    {
        // Validate grant type
        if ($request->input('grant_type') !== 'client_credentials') {
            return response()->json([
                'error' => 'unsupported_grant_type',
                'error_description' => 'Only client_credentials grant type is supported'
            ], 400);
        }

        // Get client credentials
        $clientId = $request->input('client_id');
        $clientSecret = $request->input('client_secret');
        $requestedScope = $request->input('scope', '');

        if (!$clientId || !$clientSecret) {
            return response()->json([
                'error' => 'invalid_request',
                'error_description' => 'client_id and client_secret are required'
            ], 400);
        }

        // Find and verify client
        $client = OAuthClient::where('client_id', $clientId)
            ->where('is_active', true)
            ->first();

        if (!$client || !$client->verifySecret($clientSecret)) {
            return response()->json([
                'error' => 'invalid_client',
                'error_description' => 'Invalid client credentials'
            ], 401);
        }

        // Validate requested scopes
        $requestedScopes = array_filter(explode(' ', $requestedScope));
        $grantedScopes = [];

        if (empty($requestedScopes)) {
            $grantedScopes = $client->scopes;
        } else {
            foreach ($requestedScopes as $scope) {
                if ($client->hasScope($scope)) {
                    $grantedScopes[] = $scope;
                }
            }
        }

        if (empty($grantedScopes)) {
            return response()->json([
                'error' => 'invalid_scope',
                'error_description' => 'Requested scope is not available'
            ], 400);
        }

        // Generate access token using Sanctum
        $expiresAt = Carbon::now()->addMinutes(env('OAUTH_TOKEN_EXPIRES_IN', 30)); // 30 minutes

        $token = $client->createToken(
            'oauth-client-' . $client->name,
            $grantedScopes,
            $expiresAt
        );

        // Update client usage
        $client->markAsUsed();

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => env('OAUTH_TOKEN_EXPIRES_IN', 30) * 60, // 30 minutes in seconds
            'expires_at' => $expiresAt->toISOString(), // ISO 8601 timestamp
            'scope' => implode(' ', $grantedScopes),
            'issued_at' => Carbon::now()->toISOString()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/oauth/introspect",
     *     tags={"OAuth"},
     *     summary="Introspect access token",
     *     description="Check if an access token is valid and get its metadata",
     *     operationId="introspectToken",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"token"},
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Access token to introspect"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token introspection result",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="active", type="boolean", description="Whether the token is active"),
     *             @OA\Property(property="client_id", type="string", description="Client that owns the token"),
     *             @OA\Property(property="scope", type="string", description="Token scopes"),
     *             @OA\Property(property="exp", type="integer", description="Token expiration timestamp")
     *         )
     *     )
     * )
     */
    public function introspect(Request $request): JsonResponse
    {
        $tokenString = $request->input('token');
        
        if (!$tokenString) {
            return response()->json([
                'active' => false,
                'error' => 'Token parameter is required'
            ], 400);
        }

        try {
            // Parse the token to get the token ID
            $tokenParts = explode('|', $tokenString, 2);
            if (count($tokenParts) !== 2) {
                return response()->json([
                    'active' => false,
                    'error' => 'Invalid token format'
                ]);
            }

            $tokenId = $tokenParts[0];
            
            // Find the token in the database
            $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::find($tokenId);
            
            if (!$personalAccessToken) {
                return response()->json([
                    'active' => false,
                    'error' => 'Token not found'
                ]);
            }

            // Check if token is expired
            if ($personalAccessToken->expires_at && $personalAccessToken->expires_at->isPast()) {
                return response()->json([
                    'active' => false,
                    'error' => 'Token expired'
                ]);
            }

            // Verify the token hash
            if (!hash_equals($personalAccessToken->token, hash('sha256', $tokenParts[1]))) {
                return response()->json([
                    'active' => false,
                    'error' => 'Invalid token'
                ]);
            }

            // Get the OAuth client that owns this token
            $oauthClient = $personalAccessToken->tokenable;
            
            return response()->json([
                'active' => true,
                'client_id' => $oauthClient->client_id ?? 'unknown',
                'scope' => implode(' ', $personalAccessToken->abilities),
                'exp' => $personalAccessToken->expires_at ? $personalAccessToken->expires_at->timestamp : null,
                'iat' => $personalAccessToken->created_at->timestamp,
                'application_id' => $oauthClient->application_id ?? null,
                'application_name' => $oauthClient->application->name ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'active' => false,
                'error' => 'Token validation failed'
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/oauth/revoke",
     *     tags={"OAuth"},
     *     summary="Revoke access token",
     *     description="Revoke an active access token",
     *     operationId="revokeToken",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"token"},
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Access token to revoke"
     *             ),
     *             @OA\Property(
     *                 property="client_id",
     *                 type="string",
     *                 description="Client ID (optional for verification)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token revoked successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="revoked", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Token revoked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function revoke(Request $request): JsonResponse
    {
        $tokenString = $request->input('token');
        $clientId = $request->input('client_id');
        
        if (!$tokenString) {
            return response()->json([
                'error' => 'invalid_request',
                'message' => 'Token parameter is required'
            ], 400);
        }

        try {
            // Parse the token to get the token ID
            $tokenParts = explode('|', $tokenString, 2);
            if (count($tokenParts) !== 2) {
                return response()->json([
                    'error' => 'invalid_token',
                    'message' => 'Invalid token format'
                ], 400);
            }

            $tokenId = $tokenParts[0];
            
            // Find the token in the database
            $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::find($tokenId);
            
            if (!$personalAccessToken) {
                return response()->json([
                    'error' => 'invalid_token',
                    'message' => 'Token not found'
                ], 400);
            }

            // Verify the token hash
            if (!hash_equals($personalAccessToken->token, hash('sha256', $tokenParts[1]))) {
                return response()->json([
                    'error' => 'invalid_token',
                    'message' => 'Invalid token'
                ], 400);
            }

            // Optional: Verify client_id if provided
            if ($clientId) {
                $oauthClient = $personalAccessToken->tokenable;
                if ($oauthClient->client_id !== $clientId) {
                    return response()->json([
                        'error' => 'invalid_client',
                        'message' => 'Token does not belong to the specified client'
                    ], 400);
                }
            }

            // Delete the token
            $personalAccessToken->delete();

            return response()->json([
                'revoked' => true,
                'message' => 'Token revoked successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'server_error',
                'message' => 'Token revocation failed'
            ], 500);
        }
    }
}
