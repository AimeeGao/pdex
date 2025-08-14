<?php

namespace Modules\Admin\Listeners;

use Modules\Admin\Events\ApplicationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleApplicationCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApplicationCreated $event): void
    {
        // TODO: Add logic for when an application is created
        // Examples:
        // - Send notification to administrators
        // - Log the creation
        // - Trigger approval workflow
        // - Send welcome email to contact person
    }
}
