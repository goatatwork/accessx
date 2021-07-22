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

    public $form_data;
    public $provisioning_record;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProvisioningRecord $provisioning_record, $form_data = [])
    {
        $this->provisioning_record = $provisioning_record;
        $this->form_data = $form_data;
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
