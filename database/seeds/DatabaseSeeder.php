<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GaSettingsSeeder::class);

        $this->call(StatesSeeder::class);

        $this->call(PlatformsAndModuleTypesSeeder::class);

        $this->call(DhcpManagementNetworkSeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);
    }
}
