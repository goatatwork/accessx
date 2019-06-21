<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SupplementalRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission::create(['name' => 'view_users']);
        // Permission::create(['name' => 'manage_users']);

        // Permission::create(['name' => 'view_network']);
        // Permission::create(['name' => 'manage_network']);

        // Permission::create(['name' => 'view_customers']);
        // Permission::create(['name' => 'manage_customers']);


        Permission::findOrCreate('view_dhcp');
        Permission::findOrCreate('manage_dhcp');
        Permission::findOrCreate('view_onts');
        Permission::findOrCreate('manage_onts');
        Permission::findOrCreate('view_system');
        Permission::findOrCreate('manage_system');
        Permission::findOrCreate('view_aggregators');
        Permission::findOrCreate('manage_aggregators');


    }
}
