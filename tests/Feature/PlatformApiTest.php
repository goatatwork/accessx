<?php

namespace Tests\Feature;

use Log;
use App\User;
use App\Platform;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlatformApiTest extends TestCase
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
    public function test_api_will_fetch_platforms()
    {
        $platforms = factory(Platform::class, 35)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/infrastructure/platforms');

        $response->assertJson([
            0 => [
                'name' => $platforms[0]->name
            ],
            20 => [
                'name' => $platforms[20]->name
            ],
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_create_a_platform()
    {
        $platform = factory(Platform::class)->make();

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/infrastructure/platforms', $platform->toArray());

        $response->assertJson([
            'name' => $platform->name
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_delete_a_platform()
    {
        $platform = factory(Platform::class)->create();

        $this->assertDatabaseHas('platforms', ['name' => $platform->name]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/infrastructure/platforms/' . $platform->id);

        $this->assertDatabaseMissing('platforms', ['name' => $platform->name]);
    }

    /**
     * @return  void
     */
    public function test_api_will_update_a_platform()
    {
        $platform = factory(Platform::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/infrastructure/platforms/' . $platform->id, ['name' => 'ThePlatform']);

        $response->assertJson([
            'name' => 'ThePlatform'
        ]);

        $this->assertDatabaseHas('platforms', ['id' => $platform->id, 'name' => 'ThePlatform']);
    }
}
