<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\OAuthClient;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateApplicationsToOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:migrate-applications {--force : Create OAuth clients even if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create OAuth clients for existing applications that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $this->info('Migrating existing applications to OAuth 2.0...');
        $this->line('');

        $applications = Application::with('oauthClient')->get();
        $created = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($applications as $application) {
            if ($application->oauthClient && !$force) {
                $this->line("⏭️  Skipping '{$application->name}' - already has OAuth client");
                $skipped++;
                continue;
            }

            try {
                // Delete existing client if force is used
                if ($force && $application->oauthClient) {
                    $application->oauthClient->delete();
                    $this->line("🗑️  Deleted existing OAuth client for '{$application->name}'");
                }

                // Generate client credentials
                $clientId = 'pdex_' . Str::random(32);
                $clientSecret = Str::random(64);

                // Create OAuth client
                $client = OAuthClient::create([
                    'application_id' => $application->id,
                    'name' => $application->name . ' API Client',
                    'client_id' => $clientId,
                    'client_secret' => bcrypt($clientSecret),
                    'scopes' => ['read', 'write'], // Default scopes
                    'is_active' => true
                ]);

                $this->line("✅ Created OAuth client for '{$application->name}'");
                $this->line("   Client ID: {$client->client_id}");
                $this->line("   Client Secret: {$clientSecret}");
                $this->line("   Base64: " . base64_encode($client->client_id . ':' . $clientSecret));
                $this->line('');

                $created++;

            } catch (\Exception $e) {
                $this->error("❌ Failed to create OAuth client for '{$application->name}': " . $e->getMessage());
                $errors++;
            }
        }

        $this->line('');
        $this->info('Migration Summary:');
        $this->line("✅ Created: {$created}");
        $this->line("⏭️  Skipped: {$skipped}");
        $this->line("❌ Errors: {$errors}");

        if ($created > 0) {
            $this->line('');
            $this->warn('⚠️  Save all Client Secrets securely - they will not be shown again!');
        }

        return Command::SUCCESS;
    }
}
