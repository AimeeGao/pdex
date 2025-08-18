<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Session;

class GatewayController extends Controller
{
    /**
     * Show auto-submitting form for POST redirect to external application
     */
    public function showRedirectForm(Request $request, Application $application)
    {
        $user = Auth::user();
        $userIdp = $user->identity_provider; // bcsc, idir, bceid
        
        // Verify user can access this application
        if ($application->trashed() || !$application->isFinallyApproved() || $application->status !== 'active') {
            return redirect()->route('dashboard')->with('error', 'Application is not currently available.');
        }
        
        $canAccess = false;
        $redirectUrl = null;
        
        if ($userIdp === 'bcsc' && $application->bcsc_enabled) {
            $canAccess = true;
            $redirectUrl = $application->bcsc_redirect_url;
        } elseif ($userIdp === 'idir' && $application->idir_enabled) {
            $canAccess = true;
            $redirectUrl = $application->idir_redirect_url;
        } elseif ($userIdp === 'bceid' && $application->bceid_enabled) {
            $canAccess = true;
            $redirectUrl = $application->bceid_redirect_url;
        }
        
        if (!$canAccess || !$redirectUrl) {
            Log::warning('User access denied or invalid redirect URL', [
                'user_id' => $user->id,
                'application_id' => $application->id,
                'can_access' => $canAccess,
                'redirect_url' => $redirectUrl,
                'user_idp' => $userIdp
            ]);
            return redirect()->route('login')->with('error', 'You do not have access to this application or the application URL is not configured.');
        }
        
        // Get the token from user record (stored during Keycloak authentication)
        $accessToken = $user->kc_token;
        $refreshToken = $user->kc_refresh_token;
        
        if (!$accessToken) {
            Log::warning('No access token found for user gateway redirect', [
                'user_id' => $user->id,
                'application_id' => $application->id,
                'user_idp' => $userIdp
            ]);
            return redirect()->route('login')->with('error', 'Authentication token not found. Please login again.');
        }
        
        $logoutUrl = Session::get('kc_logout_uri_' . $request->user()->id);

        // Prepare form data
        $formData = [
            'token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user_type' => $userIdp,
            'ud' => $user->id,
            'logoutUrl' => $logoutUrl
        ];
        
        // Log the redirect for auditing
        Log::info('User redirected to external application via POST', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'application_id' => $application->id,
            'application_name' => $application->name,
            'user_idp' => $userIdp,
            'redirect_url' => $redirectUrl
        ]);
        
        // Return auto-submitting form
        return response()->view('gateway.redirect-form', [
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'applicationName' => $application->name
        ]);
    }

    /**
     * Redirect user to external application with token and user type
     */
    /**
     * Handle POST redirect to external application (for direct API calls)
     */
    public function redirectToApplication(Request $request, Application $application): RedirectResponse
    {
        // This method can handle direct POST requests if needed
        // For most cases, users will go through showRedirectForm() instead
        return redirect()->route('gateway.redirect', $application);
    }

    public function testRedirectToApplication(Request $request, $application)
    {
        // Capture both query parameters and POST body data
        $queryParams = $request->query();
        $postData = $request->all();
        
        // Extract the token and decode it if present
        $tokenInfo = null;
        if (isset($postData['token']) && !empty($postData['token'])) {
            $token = $postData['token'];
            
            // Decode JWT token to see its contents (without verification for debugging)
            $tokenParts = explode('.', $token);
            if (count($tokenParts) === 3) {
                try {
                    // Decode the payload (second part)
                    $payload = json_decode(base64_decode(str_pad(strtr($tokenParts[1], '-_', '+/'), strlen($tokenParts[1]) % 4, '=', STR_PAD_RIGHT)), true);
                    
                    // Decode the header (first part)
                    $header = json_decode(base64_decode(str_pad(strtr($tokenParts[0], '-_', '+/'), strlen($tokenParts[0]) % 4, '=', STR_PAD_RIGHT)), true);
                    
                    $tokenInfo = [
                        'header' => $header,
                        'payload' => $payload,
                        'raw_token_length' => strlen($token),
                        'token_parts_count' => count($tokenParts)
                    ];
                } catch (\Exception $e) {
                    $tokenInfo = [
                        'error' => 'Failed to decode token: ' . $e->getMessage(),
                        'raw_token_length' => strlen($token)
                    ];
                }
            } else {
                $tokenInfo = [
                    'error' => 'Invalid JWT format',
                    'token_parts_count' => count($tokenParts),
                    'raw_token_length' => strlen($token)
                ];
            }
        }

        // Log all the captured data for debugging
        Log::info('Testing redirect to application - Full Debug', [
            'application_id' => $application,
            'request_method' => $request->method(),
            'query_params' => $queryParams,
            'post_data' => $postData,
            'token_info' => $tokenInfo,
            'headers' => $request->headers->all()
        ]);

        // Return comprehensive debug response
        return response()->json([
            'message' => 'Test successful - Debug info captured',
            'application_id' => $application,
            'request_method' => $request->method(),
            'query_params' => $queryParams,
            'post_data' => $postData,
            'token_decoded' => $tokenInfo,
            'timestamp' => now()->toISOString()
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
