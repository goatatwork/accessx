<?php

namespace Tests\Unit;

use App\Slot;
use Tests\TestCase;
use App\ModuleType;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group slots
     * @test
     */
    public function test_slot_knows_if_it_is_not_populated()
    {
        $slot = factory(Slot::class)->create();
        $this->assertFalse($slot->populated);
    }

    /**
     * @group slots
     * @test
     */
    public function test_slot_knows_if_it_is_populated()
    {
        $slot = factory(Slot::class)->create(['module_type_id' => 2]);
        $this->assertTrue($slot->populated);
    }

    /**
     * @group slots
     * @test
     */
    public function test_slot_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $slot = $provrec->port->slot;

        $this->assertTrue($slot->has_provisioning_records);
    }

    /**
     * @group slots
     * @test
     */
    public function test_slot_knows_if_it_does_not_have_provisioning_records()
    {
        $slot = factory(Slot::class)->create();

        $this->assertFalse($slot->has_provisioning_records);
    }

    /**
     * @group slots
     * @test
     */
    public function test_slot_creates_ports_when_it_is_populated()
    {
        $slot = factory(Slot::class)->create();

        $this->assertFalse($slot->populated);

        $module_type = factory(ModuleType::class)->create(['platform_id' => $slot->aggregator->platform->id]);

        $slot->populate($module_type);

        $this->assertTrue($slot->populated);
        $this->assertEquals($module_type->number_of_ports, $slot->ports()->count());
    }

    /**
     * @group slots
     * @test
     */
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

    /**
     * @group slots
     * @test
     */
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
