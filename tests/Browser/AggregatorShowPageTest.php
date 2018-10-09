<?php

namespace Tests\Browser;

use App\Port;
use App\Slot;
use App\User;
use App\Customer;
use App\Platform;
use App\IpAddress;
use App\Aggregator;
use App\ModuleType;
use App\OntProfile;
use App\BillingRecord;
use App\ServiceLocation;
use App\ProvisioningRecord;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ShowAggregator;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AggregatorShowPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @group aggregator-tests
     * @return void
     */
    public function test_a_provisioning_record_can_exist_in_the_database()
    {
        $pr = $this->makeProvisioningRecord();

        $this->assertDatabaseHas('provisioning_records', [
            'service_location_id' => $pr['service_location']->id,
            'ont_profile_id' => $pr['ont_profile']->id,
            'port_id' => $pr['port']->id,
            'ip_address_id' => $pr['ip']->id
        ]);
    }

    /**
     * Test that we see the slot with the assigned module type when we hit the
     * show aggregator page.
     *
     * @group aggregator-tests
     * @return void
     */
    public function test_aggregator_page_shows_module_type_name_on_slot()
    {
        $user = factory(User::class)->create();

        $pr = $this->makeProvisioningRecord();

        $this->browse(function($browser) use ($user, $pr) {
            $browser->loginAs($user)
                ->visit('/infrastructure/aggregators/' . $pr['aggregator']->id)
                ->assertSee('Slot ' . $pr['slot']->slot_number . ' ' . $pr['module_type']->name);

            $browser->press('Slot ' . $pr['slot']->slot_number . ' ' . $pr['module_type']->name)
                ->pause(1000)
                ->assertSee('Port ' . $pr['slot']->slot_number . ' provisioned');
        });
    }

    /**
     * A new provisioning record with everything except real ont files
     *
     * @return void
     */
    protected function makeProvisioningRecord()
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
        $ont_profile = factory(OntProfile::class)->create();
        $provisioning_record = factory(ProvisioningRecord::class)->create([
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip->id
        ]);

        $fake_provisioning_record = [
            'platform' => $platform,
            'module_type' => $module_type,
            'aggregator' => $aggregator,
            'slot' => $slot,
            'port' => $port,
            'ip' => $ip,
            'customer' => $customer,
            'billing_record' => $billing_record,
            'service_location' => $service_location,
            'ont_profile' => $ont_profile,
            'provisioning_record' => $provisioning_record
        ];

        return $fake_provisioning_record;
    }
}
