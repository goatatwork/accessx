<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        (Permission::all())->each(function($permission, $index) {
            $permission->delete();
        });
        (Role::all())->each(function($role, $index) {
            $role->delete();
        });
        (User::all())->each(function($user, $index) {
            $user->delete();
        });

        $goldfield_user = User::create(['name' => 'Goldfield Telecom', 'email' => 'support@goldtelecom.com', 'password' => bcrypt('cLHcMmpc6uXTH4YE')]);

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_network']);
        Permission::create(['name' => 'manage_customers']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'view_network']);
        Permission::create(['name' => 'view_customers']);

        // create roles and assign created permissions

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'manage_users',
            'manage_network',
            'manage_customers',
            'view_users',
            'view_network',
            'view_customers'
        ]);

        $role = Role::create(['name' => 'technician']);
        $role->givePermissionTo([
            'manage_network',
            'manage_customers',
            'view_network',
            'view_customers'
        ]);

        $role = Role::create(['name' => 'guest']);
        $role->givePermissionTo([
            'view_network',
            'view_customers'
        ]);

        $goldfield_user->assignRole('admin');
    }
}
