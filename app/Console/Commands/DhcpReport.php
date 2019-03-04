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
    protected $signature = 'goldaccess:dhcp {--report=none : A subnet in CIDR, like "192.168.200.128/25" or "192.168.201.0/24"}';

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
        if ($this->option('report') == 'none') {
            $this->info('Have a default day');
        } elseif ($this->option('report') == null) {
            $this->info('Have a null day');
        } else {
            $subnet_parts = explode("/", $this->option('report'));
            $network = $subnet_parts[0];
            $cidr = $subnet_parts[1];

            $subnet = Subnet::whereNetworkAddress($network)->whereCidr($cidr)->first();

            if ($subnet) {
                // $headers = ['Module', 'Origin Exists', 'Origin Path', 'Is Deployed', 'Deploy Path'];
                $headers = ['Module', 'Origin Exists', 'Is Deployed', 'Deploy Path'];
                $this->table($headers, $this->makeData()->toArray());
            } else {
                $this->info('I did not find that subnet');
            }
        }
    }

    public function makeData()
    {
        $subnet = Subnet::find(22);
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
