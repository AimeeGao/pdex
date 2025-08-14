<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\OAuthClient;
use Illuminate\Support\Str;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        // Automatically create an OAuth client for the new application
        $this->createOAuthClientForApplication($application);
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        // If application name changed, update OAuth client name
        if ($application->isDirty('name') && $application->oauthClient) {
            $application->oauthClient->update([
                'name' => $application->name . ' API Client'
            ]);
        }
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        // OAuth client will be deleted automatically due to cascade delete
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        // Recreate OAuth client if it doesn't exist
        if (!$application->oauthClient) {
            $this->createOAuthClientForApplication($application);
        }
    }

    /**
     * Create OAuth client for the application
     */
    private function createOAuthClientForApplication(Application $application): void
    {
        // Don't create if OAuth client already exists
        if ($application->oauthClient) {
            return;
        }

        // Generate client credentials
        $clientId = 'pdex_' . Str::random(32);
        $clientSecret = Str::random(64);

        // Create OAuth client with appropriate scopes
        OAuthClient::create([
            'application_id' => $application->id,
            'name' => $application->name . ' API Client',
            'client_id' => $clientId,
            'client_secret' => bcrypt($clientSecret),
            'scopes' => ['read', 'write'], // Default scopes, can be customized later
            'is_active' => true
        ]);

        // Log the creation for admin reference
        \Log::info('OAuth client created for application', [
            'application_id' => $application->id,
            'application_name' => $application->name,
            'client_id' => $clientId,
            // Note: Don't log the plain secret for security
        ]);
    }
}
