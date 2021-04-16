<?php

namespace App\Events;

use App\ProvisioningRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ServiceWasProvisioned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $provisioning_record;
    public $package_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProvisioningRecord $provisioning_record, $package_id = null)
    {
        $this->provisioning_record = $provisioning_record;
        $this->package_id = $package_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
    }
}
