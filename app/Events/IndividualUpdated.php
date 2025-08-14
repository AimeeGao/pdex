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

class IndividualUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Individual $individual;
    public array $originalData;
    public ?User $updatedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Individual $individual, array $originalData, ?User $updatedBy = null)
    {
        $this->individual = $individual;
        $this->originalData = $originalData;
        $this->updatedBy = $updatedBy;
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
            'updated_at' => $this->individual->updated_at,
            'updated_by' => $this->updatedBy?->name,
            'changes' => $this->getChangedFields(),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'individual.updated';
    }

    /**
     * Get the fields that were changed.
     */
    public function getChangedFields(): array
    {
        $changes = [];
        $currentData = $this->individual->toArray();

        foreach ($currentData as $key => $value) {
            if (isset($this->originalData[$key]) && $this->originalData[$key] !== $value) {
                $changes[$key] = [
                    'from' => $this->originalData[$key],
                    'to' => $value,
                ];
            }
        }

        return $changes;
    }

    /**
     * Check if verification status was changed.
     */
    public function verificationStatusChanged(): bool
    {
        return isset($this->originalData['verification_status']) &&
               $this->originalData['verification_status'] !== $this->individual->verification_status;
    }

    /**
     * Check if status was changed.
     */
    public function statusChanged(): bool
    {
        return isset($this->originalData['status']) &&
               $this->originalData['status'] !== $this->individual->status;
    }
}
