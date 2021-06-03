<?php

namespace App\Jobs;

use App\ProvisioningRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnablePort implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $provisioning_record;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param  ProvisioningRecord  $provisioning_record
     * @return void
     */
    public function __construct(ProvisioningRecord $provisioning_record)
    {
        $this->provisioning_record = $provisioning_record;
        $this->onQueue('switchtool');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->doIt();
        $this->logIt();
    }

    public function doIt()
    {
        $url = config('goldaccess.settings.switchtool_url') . '/api/ports/enable';

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'PATCH',
                'content' => http_build_query($this->patchData())
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public function logIt()
    {
        \Log::info('EnablePort for PR:' .
            $this->provisioning_record->id .
            ' on switch ' .
            $this->switchIp() .
            ' port ' .
            $this->portToChange()
        );
    }

    /**
     * Data for inclusion in PATCH to the switchtool
     * @return array Data for switchtool
     */
    protected function patchData()
    {
        return [
            'switch_ip' => $this->switchIp(),
            'port_name' => $this->portToChange()
        ];
    }

    /**
     * Name of the switchport as the switch expects it
     * @return string
     */
    protected function portToChange()
    {
        return 'ethernet' .
            $this->provisioning_record->port->slot->slot_number .
            '/1/' .
            $this->provisioning_record->port->port_number;
    }

    /**
     * The IP of the switch that this provisioning record is on
     * @return string IP of switch
     */
    protected function switchIp()
    {
        return $this->provisioning_record->port->slot->aggregator->management_ip;
    }
}
