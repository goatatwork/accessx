<?php

namespace Tests\Unit;

use App\Slot;
use App\ModuleType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return  void
     */
    public function test_slot_knows_if_it_is_not_populated()
    {
        $slot = factory(Slot::class)->create();
        $this->assertFalse($slot->populated);
    }

    /**
     * @return  void
     */
    public function test_slot_knows_if_it_is_populated()
    {
        $slot = factory(Slot::class)->create(['module_type_id' => 2]);
        $this->assertTrue($slot->populated);
    }

    public function test_slot_creates_ports_when_it_is_populated()
    {
        $slot = factory(Slot::class)->create();

        $this->assertFalse($slot->populated);

        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);

        $slot->populate($module_type);

        $this->assertTrue($slot->populated);
        $this->assertEquals($module_type->number_of_ports, $slot->ports()->count());
    }

    public function test_slot_deletes_ports_when_it_is_unpopulated()
    {
        $slot = factory(Slot::class)->create();

        $this->assertFalse($slot->populated);

        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);

        $slot->populate($module_type);

        $this->assertTrue($slot->populated);

        $slot->unpopulate();

        $this->assertFalse($slot->populated);
        $this->assertEquals(0, $slot->ports()->count());
    }


    public function test_exception_is_thrown_if_trying_to_create_ports_for_a_slot_that_already_has_ports()
    {
        $slot = factory(Slot::class)->create();

        $this->assertFalse($slot->populated);

        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);

        $slot->populate($module_type);

        $this->expectException(\App\Exceptions\SlotAlreadyHasPorts::class);

        $slot->populate($module_type);
    }
}
