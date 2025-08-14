<?php

namespace App\Events;

use App\Models\Individual;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IndividualCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Individual $individual;
    public ?User $createdBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Individual $individual, ?User $createdBy = null)
    {
        $this->individual = $individual;
        $this->createdBy = $createdBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('individual.' . $this->individual->guid),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'individual_guid' => $this->individual->guid,
            'individual_name' => $this->individual->full_name,
            'created_at' => $this->individual->created_at,
            'created_by' => $this->createdBy?->name,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'individual.created';
    }
}
