<?php

namespace App\Console\Commands;

use App\Subnet;
use Illuminate\Console\Command;

class DhcpReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goldaccess:dhcp {--report : Get current state of DHCP config files and options for a subnet}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Talk to Dhcpbot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subnets = Subnet::all()->where('is_management', false);

        $subnet = $this->choice('Which subnet would you like to report on?', $subnets->pluck('slug', 'id')->toArray());

        $this->line('DHCP Deployment Report');
        $this->table($this->makeHeaders(), $this->makeData($subnet)->toArray());
    }

    public function makeHeaders()
    {
        return ['Feature', 'Is Built', 'Is Deployed', 'Deploy Path'];
    }

    public function makeData($slug)
    {
        $subnet = Subnet::all()->where('slug', $slug)->first();

        $data = collect(app('dhcpbot')->report($subnet));

        return $data->map(function($media_library) {
            return [
                $media_library['collection'],
                ($media_library['origin_exists']) ? '✅' : '❌',
                // $media_library['origin_path'],
                ($media_library['is_deployed']) ? '✅' : '❌',
                $media_library['deploy_path'],
            ];
        });
    }
}
