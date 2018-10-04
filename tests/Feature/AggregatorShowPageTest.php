<?php

namespace Tests\Feature;

use App\Port;
use App\Slot;
use App\User;
use App\Customer;
use App\Platform;
use App\IpAddress;
use Tests\TestCase;
use App\Aggregator;
use App\ModuleType;
use App\OntProfile;
use App\BillingRecord;
use App\ServiceLocation;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AggregatorShowPageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_bs()
    {
        $this->assertTrue(true);
    }

    /**
     *
     * @return void
     */
    public function test_aggregator_page_exists()
    {
        $platform = factory(Platform::class)->create();
        $module_type = factory(ModuleType::class)->create(['platform_id' => $platform->id]);
        $aggregator = factory(Aggregator::class)->create(['platform_id' => $platform->id]);
        $slot = factory(Slot::class)->create(['aggregator_id' => $aggregator->id, 'module_type_id' => $module_type->id]);
        $port = factory(Port::class)->create(['slot_id' => $slot->id]);

        $ip = factory(IpAddress::class)->create();

        $customer = factory(Customer::class)->create();
        $billing_record = factory(BillingRecord::class)->make(['customer_id' => null]);
        $service_location = factory(ServiceLocation::class)->make(['customer_id' => null]);
        $billing_record = $customer->billing_record()->create($billing_record->toarray());
        $billing_record = $customer->billing_record()->create($billing_record->toarray());

        $ont_profile = factory(OntProfile::class)->create();

        $provisioning_record = factory(ProvisioningRecord::class)->create([
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip->id
        ]);

        $this->assertDatabaseHas('provisioning_records', [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip->id
        ]);

        $response = $this->actingAs($this->user)->get('/infrastructure/aggregators/' . $aggregator->id);
        $response->assertSee($aggregator->name);
        $response->assertSee('Slot ' . $slot->slot_number);
        $response->assertSee($module_type->name);
    }
}
