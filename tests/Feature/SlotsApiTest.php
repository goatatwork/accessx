<?php

namespace Tests\Feature;

use App\User;
use App\Slot;
use App\ModuleType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotsApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_api_will_deliver_ports_for_a_slot()
    {
        $slot = factory(Slot::class)->create();
        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);
        $slot->populate($module_type);

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/infrastructure/slots/' . $slot->id . '/ports');

        $response->assertJson([
            0 => [
                'port_number' => $slot->ports[0]->port_number
            ],
            13 => [
                'port_number' => $slot->ports[13]->port_number
            ]
        ]);
    }
}
