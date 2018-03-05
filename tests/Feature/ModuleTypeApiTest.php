<?php

namespace Tests\Feature;

use App\User;
use App\Platform;
use App\ModuleType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleTypeApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @return  void
     */
    public function test_api_will_fetch_module_types_for_a_platform()
    {
        $module_type = factory(ModuleType::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/infrastructure/platforms/' . $module_type->platform->id . '/module_types');

        $response->assertJson([
            0 => [
                'name' => $module_type->name
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_can_create_module_types()
    {
        $platform = factory(Platform::class)->create();
        $module_type = factory(ModuleType::class)->make(['platform_id' => null]);

        $response = $this->actingAs($this->user, 'api')
            ->json(
                'POST',
                '/api/infrastructure/platforms/' . $platform->id . '/module_types',
                $module_type->toArray()
            );

        $response->assertJson([
            'name' => $module_type->name
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_can_update_module_types()
    {
        $module_type = factory(ModuleType::class)->create(['number_of_ports' => 24]);

        $this->assertDatabaseHas('module_types', [
            'platform_id' => $module_type->platform->id,
            'number_of_ports' => 24
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json(
                'PATCH',
                '/api/infrastructure/module_types/' . $module_type->id,
                ['name' => 'MyModuleType', 'number_of_ports' => 48]
            );

        $response->assertJson([
            'name' => 'MyModuleType',
            'number_of_ports' => 48
        ]);

        $this->assertDatabaseHas('module_types', [
            'platform_id' => $module_type->platform->id,
            'name' => 'MyModuleType',
            'number_of_ports' => 48
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_can_delete_module_types()
    {
        $module_type = factory(ModuleType::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/infrastructure/module_types/' . $module_type->id);

        $this->assertDatabaseMissing('module_types', [
            'name' => $module_type->name,
            'number_of_ports' => $module_type->number_of_ports
        ]);
    }

    /**
     * @return  $void
     */
    public function test_api_can_fetch_a_module_type()
    {
        $module_type = factory(ModuleType::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/infrastructure/module_types/' . $module_type->id);

        $response->assertJson([
            'name' => $module_type->name,
            'number_of_ports' => $module_type->number_of_ports
        ]);
    }
}
