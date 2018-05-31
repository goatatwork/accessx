<?php

use App\DhcpSharedNetwork;
use Illuminate\Database\Seeder;

class DhcpManagementNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DhcpSharedNetwork::count())
        {
            $this->seedTheManagementNetwork();
            echo 'DhcpSharedNetwork was seeded';
            return 0;
        }
        echo 'The dhcp_shared_networks table is not empty. Skipping seeding DhcpSharedNetwork.';
        return 0;
    }

    protected function seedTheManagementNetwork()
    {
        $shared_network = DhcpSharedNetwork::create([
            'name' => 'Management',
            'management' => true,
            'vlan' => 127,
            'notes' => 'This is the default management network.'
        ]);
    }
}
