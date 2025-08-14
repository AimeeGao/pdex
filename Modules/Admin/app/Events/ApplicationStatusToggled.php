<?php

namespace Modules\Admin\Events;

use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusToggled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public string $previousStatus;
    public string $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application, string $previousStatus, string $newStatus)
    {
        $this->application = $application;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }
}
