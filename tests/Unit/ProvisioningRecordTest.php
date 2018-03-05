<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvisioningRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_provisioning_record_can_exist()
    {
        $pr = factory(ProvisioningRecord::class)->create();
        $this->assertDatabaseHas('provisioning_records', ['len' => $pr->len]);
    }

    public function test_provisioning_record_knows_its_own_tag()
    {
        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $aggregator = $provisioning_record->port->slot->aggregator->slug;
        $slot = $provisioning_record->port->slot->slot_number;
        $port = $provisioning_record->port->port_number;
        $tag = $aggregator . '-' . $slot . '-' . $port;
        $tag_unique = $tag . '-' . $provisioning_record->id;

        $this->assertEquals($provisioning_record->port_tag, $tag);
        $this->assertEquals($provisioning_record->port_tag_unique, $tag_unique);
    }
}
