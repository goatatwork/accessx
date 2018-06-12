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
        if (config('goldaccess.settings.ga_devmode'))
        {
            $this->reportDevMode();
            return;
        }

        $ont = new ZhoneOnt($this->provisioning_record->ip_address->address);

        if ( ! $ont->connected )
        {
            $this->reportNoConnection();
            return;
        }

        $ont = $ont->login(config('goldaccess.onts.defaults.user'), config('goldaccess.onts.defaults.password'));

        if ($ont->logged_in) {
            $ont->factoryReset();
            $this->reportFactoryReset();
            return;
        } else {
            $ont = $ont->login(config('goldaccess.onts.factory.user'), config('goldaccess.onts.factory.password'));
            if ($ont->logged_in) {
                $ont->factoryReset();
                $this->reportFactoryResetSecondaryAuth();
                return;
            } else {
                $this->reportAuthFailure();
                return;
            }
        }
    }

    protected function reportDevMode()
    {
            app('logbot')->log('The ONT for ' .
                    $this->provisioning_record->service_location->customer->customer_name .
                    ' at ' .
                    $this->provisioning_record->ip_address->address .
                    ' was NOT reset to factory defaults because we are in dev mode.', 'notice'
                );
    }

    protected function reportAuthFailure()
    {
        app('logbot')->log('The ONT for ' .
                $this->provisioning_record->service_location->customer->customer_name .
                ' at ' .
                $this->provisioning_record->ip_address->address .
                ' was NOT reset to factory defaults so due to an authentication failure.'
            );
    }

    protected function reportFactoryReset()
    {
        app('logbot')->log('The ONT for ' .
                $this->provisioning_record->service_location->customer->customer_name .
                ' at ' .
                $this->provisioning_record->ip_address->address .
                ' was reset to factory defaults so that it can get its new config.'
            );
    }

    protected function reportFactoryResetSecondaryAuth()
    {
        app('logbot')->log('The ONT for ' .
                $this->provisioning_record->service_location->customer->customer_name .
                ' at ' .
                $this->provisioning_record->ip_address->address .
                ' was reset to factory defaults, using FACTORY_DEFAULT_ONT_USER and FACTORY_DEFAULT_ONT_PASSWORD, so that it can get its new config.'
            );
    }

    protected function reportNoConnection()
    {
            app('logbot')->log('The ONT for ' .
                $this->provisioning_record->service_location->customer->customer_name .
                ' at ' .
                $this->provisioning_record->ip_address->address .
                ' was NOT reset to factory defaults because I could not connect to it.', 'warning'
            );
    }
}
