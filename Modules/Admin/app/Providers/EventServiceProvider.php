<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Admin\Events\ApplicationCreated;
use Modules\Admin\Events\ApplicationUpdated;
use Modules\Admin\Events\ApplicationSecurityApprovalChanged;
use Modules\Admin\Events\ApplicationPrivacyApprovalChanged;
use Modules\Admin\Events\ApplicationStatusToggled;
use Modules\Admin\Listeners\HandleApplicationCreated;
use Modules\Admin\Listeners\HandleApplicationUpdated;
use Modules\Admin\Listeners\HandleApplicationSecurityApprovalChanged;
use Modules\Admin\Listeners\HandleApplicationPrivacyApprovalChanged;
use Modules\Admin\Listeners\HandleApplicationStatusToggled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        ApplicationCreated::class => [
            HandleApplicationCreated::class,
        ],
        ApplicationUpdated::class => [
            HandleApplicationUpdated::class,
        ],
        ApplicationSecurityApprovalChanged::class => [
            HandleApplicationSecurityApprovalChanged::class,
        ],
        ApplicationPrivacyApprovalChanged::class => [
            HandleApplicationPrivacyApprovalChanged::class,
        ],
        ApplicationStatusToggled::class => [
            HandleApplicationStatusToggled::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = false;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
