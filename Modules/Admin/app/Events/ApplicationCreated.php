<?php

namespace Modules\Admin\Events;

use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }
}
