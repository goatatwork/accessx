<?php

namespace Tests\Feature;

use App\Ont;
use App\Platform;
use App\Customer;
use Tests\TestCase;
use App\OntProfile;
use App\Aggregator;
use App\ModuleType;
use App\DhcpSharedNetwork;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlugsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_aggregators_have_slugs()
    {
        $aggregator = factory(Aggregator::class)->create(['name' => 'My Aggregator']);

        $this->assertDatabaseHas('aggregators', ['slug' => 'my-aggregator']);
    }

    /**
     * @return void
     */
    public function test_module_types_have_slugs()
    {
        $module_type = factory(ModuleType::class)->create(['name' => 'My Module Type']);

        $this->assertDatabaseHas('module_types', ['slug' => 'my-module-type']);
    }

    /**
     * @return void
     */
    public function test_platforms_have_slugs()
    {
        $platform = factory(Platform::class)->create(['name' => 'My Platform']);

        $this->assertDatabaseHas('platforms', ['slug' => 'my-platform']);
    }

    /**
     * @return void
     */
    public function test_dhcp_shared_networks_have_slugs()
    {
        $shared_network = factory(DhcpSharedNetwork::class)->create(['name' => 'My Shared Network']);

        $this->assertDatabaseHas('dhcp_shared_networks', ['slug' => 'my-shared-network']);
    }

    /**
     * @return void
     */
    public function test_customers_have_slugs()
    {
        $customer1 = factory(Customer::class)->create(['company_name' => null, 'first_name' => 'John', 'last_name' => 'Doe']);
        $customer2 = factory(Customer::class)->create(['company_name' => 'Goldfield Telecom', 'first_name' => 'John', 'last_name' => 'Doe']);

        $this->assertDatabaseHas('customers', ['id' => $customer1->id, 'slug' => 'john-doe']);
        $this->assertDatabaseHas('customers', ['id' => $customer2->id, 'slug' => 'goldfield-telecom-john-doe']);
    }

    /**
     * @return void
     */
    public function test_onts_have_slugs()
    {
        $ont = factory(Ont::class)->create(['model_number' => 'ZNID2426A', 'manufacturer' => 'Zhone']);

        $this->assertDatabaseHas('onts', ['slug' => 'zhone-znid2426a']);
    }

    /**
     * @return void
     */
    public function test_ont_profiles_have_slugs()
    {
        $ont = factory(OntProfile::class)->create(['name' => '20 x 20 Package']);

        $this->assertDatabaseHas('ont_profiles', ['slug' => '20-x-20-package']);
    }
}
