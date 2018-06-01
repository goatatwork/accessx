<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Aggregator;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AggregatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_aggregator_can_make_empty_slots()
    {
        $aggregator = factory(Aggregator::class)->create();
        $aggregator->createEmptySlots();
        $this->assertDatabaseHas('aggregators', ['management_ip' => $aggregator->management_ip]);
    }

    public function test_exception_is_thrown_if_trying_to_create_slots_for_an_aggreator_that_already_has_slots()
    {
        $aggregator = factory(Aggregator::class)->create();
        $aggregator->createEmptySlots();
        $this->expectException(\App\Exceptions\AggregatorAlreadyHasSlots::class);
        $aggregator->createEmptySlots();
        $this->assertDatabaseMissing('aggregators', ['management_ip' => $aggregator->management_ip]);
    }

    public function test_an_aggregator_has_a_slug()
    {
        $aggregator = factory(Aggregator::class)->create(['name' => 'New Aggregator']);

        $this->assertEquals('new-aggregator', $aggregator->slug);
    }

    public function test_aggregator_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $aggregator = $provrec->port->slot->aggregator;

        $this->assertTrue($aggregator->has_provisioning_records);
    }

    public function test_aggregator_knows_if_it_does_not_have_provisioning_records()
    {
        $aggregator = factory(Aggregator::class)->create();

        $this->assertFalse($aggregator->has_provisioning_records);
    }
}
