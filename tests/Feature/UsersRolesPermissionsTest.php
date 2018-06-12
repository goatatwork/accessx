<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
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

    public function test_a_role_can_be_fetched()
    {
        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/authorization/roles/' . $this->admin_role->id);

        $response->assertJsonFragment([
            'name' => 'admin',
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

    public function test_api_returns_first_role_for_a_user_via_user_role_resource()
    {
        $admin_user = (factory(User::class)->create())->assignRole('admin');

        $admin_role = Role::whereName('admin')->first();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/authorization/users/' . $admin_user->id . '/role');

        $response->assertJson([
            'id' => $admin_role->id,
            'name' => $admin_role->name
        ]);
    }

    public function test_api_will_update_a_user_name_when_not_resetting_password()
    {
        $admin_user = (factory(User::class)->create())->assignRole('admin');

        $new_admin_user_info = factory(User::class)->make();

        $patch_data = [
            'id' => $admin_user->id,
            'name' => $new_admin_user_info->name,
            'email' => $admin_user->email,
            'reset_password' => false,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'technician'
        ];

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/authorization/users/' . $admin_user->id, $patch_data);

        $response->assertJson([
            'name' => $new_admin_user_info->name,
            'email' => $admin_user->email,
        ]);
    }

    public function test_api_will_update_a_user_name_when_resetting_password()
    {
        $admin_user = (factory(User::class)->create())->assignRole('admin');

        $new_admin_user_info = factory(User::class)->make();

        $patch_data = [
            'id' => $admin_user->id,
            'name' => $new_admin_user_info->name,
            'email' => $admin_user->email,
            'reset_password' => true,
            'password' => '1q2w3e4r5t',
            'password_confirmation' => '1q2w3e4r5t',
            'role' => 'technician'
        ];

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/authorization/users/' . $admin_user->id, $patch_data);

        $response->assertJson([
            'name' => $new_admin_user_info->name,
            'email' => $admin_user->email,
        ]);

        $hash = bcrypt('1q2w3e4r5t');
        $this->assertTrue(Hash::check('1q2w3e4r5t', $hash));

    }

    public function test_api_will_error_when_updating_a_user_when_password_reset_is_true_but_password_is_empty()
    {
        $admin_user = (factory(User::class)->create())->assignRole('admin');

        $new_admin_user_info = factory(User::class)->make();

        $patch_data = [
            'id' => $admin_user->id,
            'name' => $admin_user->name,
            'email' => $new_admin_user_info->email,
            'reset_password' => true,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'technician'
        ];

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/authorization/users/' . $admin_user->id, $patch_data);

        $response->assertJson([
            'errors' => [
                'password' => [
                    'The password field is required.'
                ]
            ]
        ]);
    }

    public function test_api_will_error_when_updating_a_user_when_password_confirmation_fails()
    {
        $admin_user = (factory(User::class)->create())->assignRole('admin');

        $new_admin_user_info = factory(User::class)->make();

        $patch_data = [
            'id' => $admin_user->id,
            'name' => $admin_user->name,
            'email' => $new_admin_user_info->email,
            'reset_password' => true,
            'password' => '1q2w3e4r',
            'password_confirmation' => '1q2w3e4r5t6y7u8i9o0p',
            'role' => 'technician'
        ];

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/authorization/users/' . $admin_user->id, $patch_data);

        $response->assertJson([
            'errors' => [
                'password' => [
                    'The password confirmation does not match.'
                ]
            ]
        ]);
    }

    public function test_api_will_create_user_with_role()
    {
        $userForm = [
            'name' => 'Test User Name',
            'email' => 'testuser@example.com',
            'role' => 'guest',
            'password' => '1q2w3e4r5t6y',
            'password_confirmation' => '1q2w3e4r5t6y',
        ];

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/authorization/users', $userForm);

        $response->assertJson([
            'name' => 'Test User Name',
            'email' => 'testuser@example.com',
        ]);

        $newuser = User::whereName('Test User Name')->first();

        $this->assertTrue($newuser->hasRole('guest'));
        $this->assertFalse($newuser->hasRole('admin'));
    }

    public function test_api_will_delete_user()
    {
        $user = factory(User::class)->create();

        $this->assertCount(1, User::whereName($user->name)->get());

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/authorization/users/' . $user->id);

        $this->assertCount(0, User::whereName($user->name)->get());
    }

    public function test_api_will_toggle_role_permissions()
    {
        $this->assertTrue($this->guest_role->hasPermissionTo('view_customers'));
        $this->assertFalse($this->guest_role->hasPermissionTo('view_users'));

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/authorization/roles/' . $this->guest_role->id . '/permissions/view_customers/toggle');

        $guest = Role::whereName('guest')->first();
        $this->assertFalse($guest->hasPermissionTo('view_customers'));

        $response1 = $this->actingAs($this->user, 'api')->json('PATCH', '/api/authorization/roles/' . $this->guest_role->id . '/permissions/view_users/toggle');

        $guest1 = Role::whereName('guest')->first();
        $this->assertTrue($guest1->hasPermissionTo('view_users'));
    }
}
