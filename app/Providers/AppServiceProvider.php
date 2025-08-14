<?php

namespace App\Providers;

use App\Models\Application;
use App\Observers\ApplicationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production' || config('app.env') === 'development') {
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Application observer to auto-create OAuth clients
        Application::observe(ApplicationObserver::class);
    }
}
