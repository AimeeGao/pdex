<?php

namespace App\Console\Commands;

use App\Models\OAuthClient;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateOAuthClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:client 
                            {name? : Name for the OAuth client (or application name)} 
                            {--app-id= : Create OAuth client for existing application ID}
                            {--scopes=* : Allowed scopes for the client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new OAuth 2.0 client for API access or for an existing application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applicationId = $this->option('app-id');
        $scopes = $this->option('scopes') ?: ['read', 'write'];

        if ($applicationId) {
            return $this->createOAuthClientForApplication($applicationId, $scopes);
        }

        return $this->createStandaloneOAuthClient($scopes);
    }

    /**
     * Create OAuth client for an existing application
     */
    private function createOAuthClientForApplication($applicationId, $scopes)
    {
        $application = \App\Models\Application::find($applicationId);
        
        if (!$application) {
            $this->error("Application with ID {$applicationId} not found.");
            return Command::FAILURE;
        }

        // Check if application already has an OAuth client
        if ($application->oauthClient) {
            $this->error("Application '{$application->name}' already has an OAuth client.");
            $this->line('Client ID: ' . $application->oauthClient->client_id);
            return Command::FAILURE;
        }

        // Generate client credentials
        $clientId = 'pdex_' . Str::random(32);
        $clientSecret = Str::random(64);

        // Create the client
        $client = OAuthClient::create([
            'application_id' => $application->id,
            'name' => $application->name . ' API Client',
            'client_id' => $clientId,
            'client_secret' => bcrypt($clientSecret),
            'scopes' => $scopes,
            'is_active' => true
        ]);

        $this->displayClientInfo($client, $clientSecret, $application->name);
        return Command::SUCCESS;
    }

    /**
     * Create standalone OAuth client
     */
    private function createStandaloneOAuthClient($scopes)
    {
        $name = $this->argument('name');
        
        if (!$name) {
            $name = $this->ask('Enter the name for the OAuth client');
        }

        // Generate client credentials
        $clientId = 'pdex_' . Str::random(32);
        $clientSecret = Str::random(64);

        // Create the client
        $client = OAuthClient::create([
            'application_id' => null, // Standalone client
            'name' => $name,
            'client_id' => $clientId,
            'client_secret' => bcrypt($clientSecret),
            'scopes' => $scopes,
            'is_active' => true
        ]);

        $this->displayClientInfo($client, $clientSecret);
        return Command::SUCCESS;
    }

    /**
     * Display client information
     */
    private function displayClientInfo($client, $plainSecret, $applicationName = null)
    {
        $this->info('OAuth Client created successfully!');
        $this->line('');
        
        if ($applicationName) {
            $this->line('Application: ' . $applicationName);
        }
        
        $this->line('Client Name: ' . $client->name);
        $this->line('Client ID: ' . $client->client_id);
        $this->line('Client Secret: ' . $plainSecret);
        $this->line('Scopes: ' . implode(', ', $client->scopes));
        $this->line('');
        $this->warn('⚠️  Save the Client Secret securely - it will not be shown again!');
        $this->line('');
        $this->line('📋 Base64 Encoded Credentials (for Authorization header):');
        $this->line(base64_encode($client->client_id . ':' . $plainSecret));
        $this->line('');
        $this->line('🔗 Token endpoint: POST /api/oauth/token');
        $this->line('   Content-Type: application/x-www-form-urlencoded');
        $this->line('   Body: grant_type=client_credentials&client_id=' . $client->client_id . '&client_secret=' . $plainSecret);
    }
}
