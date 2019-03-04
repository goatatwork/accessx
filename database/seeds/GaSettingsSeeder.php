<?php

use App\GaSetting;
use Illuminate\Database\Seeder;

class GaSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ga_settings')->truncate();
        GaSetting::create([
            'name' => 'dhcp_default_lease_time',
            'value' => '10m',
            'description' => 'The default length of any lease, in seconds'
        ]);
        GaSetting::create([
            'name' => 'acs_url',
            'value' => '',
            'description' => 'The value used in DHCP option 43 to specify the URL of the ACS server'
        ]);
    }
}
