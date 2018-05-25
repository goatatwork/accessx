<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersRolesPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        // first include all the normal setUp operations
        parent::setUp();

        $this->admin_role = Role::create(['name' => 'admin']);
        $this->technician_role =  Role::create(['name' => 'technician']);
        $this->guest_role =  Role::create(['name' => 'guest']);

        $this->perm1 = Permission::create(['name' => 'manage_users']);
        $this->perm2 = Permission::create(['name' => 'manage_network']);
        $this->perm3 = Permission::create(['name' => 'manage_customers']);
        $this->perm4 = Permission::create(['name' => 'view_users']);
        $this->perm5 = Permission::create(['name' => 'view_network']);
        $this->perm6 = Permission::create(['name' => 'view_customers']);

        $this->admin_role->givePermissionTo([
            'manage_users',
            'manage_network',
            'manage_customers',
            'view_users',
            'view_network',
            'view_customers'
        ]);

        $this->technician_role->givePermissionTo([
            'manage_network',
            'manage_customers',
            'view_network',
            'view_customers'
        ]);

        $this->guest_role->givePermissionTo([
            'view_network',
            'view_customers'
        ]);

        // now re-register all the roles and permissions
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_users_page_exists()
    {
        $response = $this->actingAs($this->user)->get('/users');

        $response->assertStatus(200);

        $response->assertViewHas(['users', 'roles', 'permissions']);
    }

    public function test_users_can_be_fetched()
    {
        $admin_user = (factory(User::class)->create())->assignRole('admin');
        $technician_user = (factory(User::class)->create())->assignRole('technician');
        $guest_user = (factory(User::class)->create())->assignRole('guest');

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/authorization/users');

        $response->assertJsonCount(4);

        $response->assertJsonFragment([
            'name' => $admin_user->name,
        ]);

        $response->assertJsonFragment([
            'name' => $technician_user->name,
        ]);

        $response->assertJsonFragment([
            'name' => $guest_user->name,
        ]);
    }

    public function test_roles_can_be_fetched()
    {
        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/authorization/roles');

        $response->assertJsonCount(3);

        $response->assertJsonFragment([
            'name' => 'technician'
        ]);
    }

    public function test_permissions_can_be_fetched()
    {
        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/authorization/permissions');

        $response->assertJsonCount(6);

        $response->assertJsonFragment([
            'name' => 'manage_users'
        ]);
    }
}
