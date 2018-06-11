<?php

namespace App\Jobs;

use App\ProvisioningRecord;
use Illuminate\Bus\Queueable;
use App\GoldAccess\Ont\ZhoneOnt;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RebootOnt implements ShouldQueue
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
        $this->onQueue('onts');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (config('goldaccess.settings.ga_devmode'))
            {
                app('logbot')->log('The ONT for ' .
                        $this->provisioning_record->service_location->customer->customer_name .
                        ' at ' .
                        $this->provisioning_record->ip_address->address .
                        ' was NOT reset to factory defaults because we are in dev mode.', 'notice'
                    );
            } else {
                $ont = new ZhoneOnt($this->provisioning_record->ip_address->address);

                if ($ont->isConnected())
                {
                    $ont->login(config('goldaccess.onts.defaults.user'), config('goldaccess.onts.defaults.password'));
                    $ont->factoryReset();
                    app('logbot')->log('The ONT for ' .
                            $this->provisioning_record->service_location->customer->customer_name .
                            ' at ' .
                            $this->provisioning_record->ip_address->address .
                            ' was reset to factory defaults so that it can get its new config.'
                        );
                    return true;
                } else {
                    app('logbot')->log('The ONT for ' .
                        $this->provisioning_record->service_location->customer->customer_name .
                        ' at ' .
                        $this->provisioning_record->ip_address->address .
                        ' was NOT reset to factory defaults because I could not connect to it.', 'warning'
                    );
                }
            }
        } catch (\Bestnetwork\Telnet\TelnetException $e) {
            \Log::info($e);
            app('logbot')->log('There was a problem telnetting to an ONT at ' . $this->provisioning_record->ip_address->address);
        }
    }
}
