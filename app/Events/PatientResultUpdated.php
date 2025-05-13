<?php

namespace App\Events;

use App\Models\PatientResult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PatientResultUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public PatientResult $patientResult
    ) {}

    public function broadcastWith(): array
    {
        return $this->patientResult
            ->load([
                'patient.diagnosis',
                'scenario',
                'status',
                'sender_department',
                'from_department',
                'to_department',
                'user'
            ])
            ->toArray();
    }

    public function broadcastQueue(): string
    {
        return 'default';
    }

    public function broadcastOn(): PrivateChannel
    {
        $senderDepartmentId = $this->patientResult->sender_department_id;

        return new PrivateChannel(config('reverb.name') . '.department.' . $senderDepartmentId);
    }
}
