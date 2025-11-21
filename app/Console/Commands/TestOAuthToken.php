<?php

namespace App\Console\Commands;

use App\Services\OAuthTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestOAuthToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:test {--endpoint=test : The API endpoint to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OAuth token generation and API authentication';

    /**
     * Execute the console command.
     */
    public function handle(OAuthTokenService $oauthService): int
    {
        $this->info('Testing OAuth Token Service...');

        try {
            // Test token generation
            $this->info('🔑 Requesting access token...');
            $token = $oauthService->getAccessToken();
            
            if (!$token) {
                $this->error('❌ Failed to obtain access token');
                return 1;
            }

            $this->info('✅ Access token obtained successfully');
            $this->info('Token preview: ' . substr($token, 0, 20) . '...');

            // Test token validation
            $this->info('🔍 Validating token...');
            $tokenData = $oauthService->validateToken($token);
            
            if (!$tokenData) {
                $this->error('❌ Token validation failed');
                return 1;
            }

            $this->info('✅ Token validation successful');
            $this->table(['Claim', 'Value'], [
                ['aud', $tokenData['aud'] ?? 'N/A'],
                ['exp', isset($tokenData['exp']) ? date('Y-m-d H:i:s', $tokenData['exp']) : 'N/A'],
                ['iat', isset($tokenData['iat']) ? date('Y-m-d H:i:s', $tokenData['iat']) : 'N/A'],
                ['iss', $tokenData['iss'] ?? 'N/A'],
            ]);

            // Test API endpoint
            $endpoint = $this->option('endpoint');
            $apiUrl = 'http://localhost/api/v1/' . $endpoint;
            
            $this->info("🌐 Testing API endpoint: {$apiUrl}");
            
            $response = Http::withToken($token)
                ->acceptJson()
                ->get($apiUrl);

            if ($response->successful()) {
                $this->info('✅ API request successful');
                $this->info('Response: ' . $response->body());
            } else {
                $this->error('❌ API request failed');
                $this->error('Status: ' . $response->status());
                $this->error('Response: ' . $response->body());
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
