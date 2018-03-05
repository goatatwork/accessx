<?php

namespace Tests\Feature;

use App\Slot;
use App\User;
use App\ModuleType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PopulateSlotTest extends TestCase
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
    public function test_api_can_populate_a_slot()
    {
        $slot = factory(Slot::class)->create();
        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);

        $this->assertFalse($slot->populated);

        $response = $this->actingAs($this->user, 'api')
            ->json(
                'POST',
                '/api/infrastructure/slots/' . $slot->id . '/populate',
                ['module_type_id' => $module_type->id]
            );

        $response->assertJson([
            'slot_number' => $slot->slot_number,
            'module_type_id' => $module_type->id,
            'ports' => [
                0 => [
                    'port_number' => 1
                ]
            ]
        ]);

        $theSlot = Slot::find($slot->id);
        $this->assertTrue($theSlot->populated);
    }

    /**
     * @return  void
     */
    public function test_api_can_unpopulate_a_slot()
    {
        $slot = factory(Slot::class)->create();
        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);

        $this->assertFalse($slot->populated);

        $response = $this->actingAs($this->user, 'api')
            ->json(
                'POST',
                '/api/infrastructure/slots/' . $slot->id . '/populate',
                ['module_type_id' => $module_type->id]
            );

        $response->assertJson([
            'slot_number' => $slot->slot_number,
            'module_type_id' => $module_type->id
        ]);

        $theSlot = Slot::find($slot->id);
        $this->assertTrue($theSlot->populated);

        //////////

        $response = $this->actingAs($this->user, 'api')
            ->json(
                'POST',
                '/api/infrastructure/slots/' . $slot->id . '/unpopulate'
            );

        $response->assertJson([
            'slot_number' => $slot->slot_number,
            'module_type_id' => null
        ]);

        $theSlot = Slot::find($slot->id);
        $this->assertFalse($theSlot->populated);
    }

    public function test_non_api_form_can_populate_slots()
    {
        $slot = factory(Slot::class)->create();

        $module_types = factory(ModuleType::class, 5)->create(['platform_id' => $slot->aggregator->platform->id]);

        $this->assertFalse($slot->populated);
        $this->assertDatabaseMissing('slots', ['id' => $slot->id, 'module_type_id' => $module_types[0]->id]);

        $response = $this->actingAs($this->user)->post('/infrastructure/slots/'.$slot->id.'/populate', ['module_type_id' => $module_types[0]->id]);

        $this->assertDatabaseHas('slots', ['id' => $slot->id, 'module_type_id' => $module_types[0]->id]);

        $this->assertTrue((Slot::find($slot->id))->populated);
    }

    public function test_non_api_form_can_unpopulate_slots()
    {
        $slot = factory(Slot::class)->create();

        $module_types = factory(ModuleType::class, 5)->create(['platform_id' => $slot->aggregator->platform->id]);

        $this->assertFalse($slot->populated);
        $this->assertDatabaseMissing('slots', ['id' => $slot->id, 'module_type_id' => $module_types[0]->id]);

        $response = $this->actingAs($this->user)->post('/infrastructure/slots/'.$slot->id.'/populate', ['module_type_id' => $module_types[0]->id]);

        $this->assertDatabaseHas('slots', ['id' => $slot->id, 'module_type_id' => $module_types[0]->id]);

        $this->assertTrue((Slot::find($slot->id))->populated);

        $response2 = $this->actingAs($this->user)->post('/infrastructure/slots/'.$slot->id.'/unpopulate');

        $this->assertFalse((Slot::find($slot->id))->populated);
        $this->assertDatabaseMissing('slots', ['id' => $slot->id, 'module_type_id' => $module_types[0]->id]);
    }
}
