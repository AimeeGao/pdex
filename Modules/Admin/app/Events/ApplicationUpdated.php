<?php

namespace Modules\Admin\Events;

use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public array $changes;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application, array $changes = [])
    {
        $this->application = $application;
        $this->changes = $changes;
    }
}
