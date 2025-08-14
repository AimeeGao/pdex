<?php

namespace Modules\Admin\Events;

use App\Models\Application;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationSecurityApprovalChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public User $approver;
    public string $previousStatus;
    public string $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application, User $approver, string $previousStatus, string $newStatus)
    {
        $this->application = $application;
        $this->approver = $approver;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }
}
