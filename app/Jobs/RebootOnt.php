<?php

namespace App\Jobs;

use App\ProvisioningRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RebootOnt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $provisioning_record;

    /**
     * Create a new job instance.
     *
     * @param  ProvisioningRecord  $provisioning_record
     * @return void
     */
    public function __construct(ProvisioningRecord $provisioning_record)
    {
        $this->provisioning_record = $provisioning_record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (config('goldaccess.settings.devmode'))
        {
            app('logbot')->log('The ONT for ' .
                    $provisioning_record->service_location->customer->customer_name .
                    ' at ' .
                    $this->provisioning_record->ip_address->address .
                    ' was NOT reset to factory defaults because we are in dev mode.'
                );
        } else {
            $ont = new ZhoneOnt($this->provisioning_record->ip_address->address);
            $ont->login(config('goldaccess.onts.defaults.user'), config('goldaccess.onts.defaults.password'));
            $ont->factoryReset();
            app('logbot')->log('The ONT for ' .
                    $provisioning_record->service_location->customer->customer_name .
                    ' at ' .
                    $this->provisioning_record->ip_address->address .
                    ' was reset to factory defaults.'
                );
            return true;
        }
    }
}
