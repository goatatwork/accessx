<?php

namespace App\Jobs;

use App\Package;
use App\ProvisioningRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetRateLimit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $package_id;
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
    public function __construct($package_id, ProvisioningRecord $provisioning_record)
    {
        $this->package_id = $package_id;
        $this->provisioning_record = $provisioning_record;
        $this->onQueue('onts');
        \Log::info('Contstructing SetRateLimit::class. with a package id of ' . $this->package_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Handling SetRateLimit::class.');
    }

}
