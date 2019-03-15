<?php

namespace Tests\Feature;

use App\Port;
use App\Slot;
use App\User;
use App\Aggregator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdditionalSlotPortsTest extends TestCase
{
    /**
     * @group ports
     * @test
     */
    public function the_api_will_create_an_ad_hoc_port_for_a_slot()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $port = factory(Port::class)->create();

        $new_port = [
            'port_number' => 1,
            'module' => 2
        ];

        $response = $this->actingAs($user, 'api')->json('POST', '/api/infrastructure/slots/' . $port->slot->id . '/ports', $new_port);

        $response->assertJson([
            'slot_id' => $port->slot->id,
            'port_number' => 1,
            'module' => 2
        ]);
    }

}
