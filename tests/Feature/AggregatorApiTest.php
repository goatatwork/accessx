<?php

namespace Tests\Feature;

use App\User;
use App\Platform;
use App\Aggregator;
use App\ModuleType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AggregatorApiTest extends TestCase
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
    public function test_api_can_fetch_aggregators()
    {
        $aggregators = factory(Aggregator::class, 5)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/infrastructure/aggregators');

        $response->assertJson([
            0 => [
                'management_mac' => $aggregators[0]->management_mac
            ],
            3 => [
                'management_mac' => $aggregators[3]->management_mac
            ]
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $aggregators);
    }

    /**
     * @return  void
     */
    public function test_api_can_create_aggregators()
    {
        $platform = factory(Platform::class)->create();
        $aggregator = factory(Aggregator::class)->make(['platform_id' => $platform->id]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST',
                '/api/infrastructure/aggregators',
                $aggregator->toArray()
            );

        $response->assertJson([
            'name' => $aggregator->name,
            'fqdn' => $aggregator->fqdn,
            'management_ip' => $aggregator->management_ip,
            'monitoring_ip' => $aggregator->monitoring_ip,
            'management_mac' => $aggregator->management_mac,
        ]);

        $theAggregator = Aggregator::whereManagementMac($aggregator->management_mac)->first();

        $this->assertEquals($theAggregator->slots()->count(), $theAggregator->platform->number_of_slots);
    }

    /**
     * @return  void
     */
    public function test_api_can_modify_an_aggregator()
    {
        $aggregator = factory(Aggregator::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH',
                'api/infrastructure/aggregators/' . $aggregator->id,
                ['name' => 'MyAggregator']
            );

        $response->assertJson([
            'name' => 'MyAggregator'
        ]);

        $this->assertDatabaseHas('aggregators', ['id' => $aggregator->id, 'name' => 'MyAggregator']);
    }

    /**
     * @return  void
     */
    public function test_api_can_delete_an_aggregator()
    {
        $aggregator = factory(Aggregator::class)->create();

        $this->assertDatabaseHas('aggregators', ['id' => $aggregator->id]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/infrastructure/aggregators/' . $aggregator->id);

        $this->assertDatabaseMissing('aggregators', ['id' => $aggregator->id]);
    }

    /**
     * @return  voide
     */
    public function test_api_will_fetch_slots_for_an_aggregator()
    {
        $aggregator = factory(Aggregator::class)->create();
        $aggregator->createEmptySlots();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/infrastructure/aggregators/' . $aggregator->id . '/slots');

        $response->assertJson([
            0 => [
                'slot_number' => 1
            ],
            1 => [
                'slot_number' => 2
            ],
            2 => [
                'slot_number' => 3
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_return_module_types_for_an_aggregator()
    {
        $aggregator = factory(Aggregator::class)->create();
        $platform = $aggregator->platform;

        $module_types = factory(ModuleType::class, 5)->create(['platform_id' => $platform->id]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/infrastructure/aggregators/' . $aggregator->id . '/module_types');

        $response->assertJson([
            0 => [
                'name' => $module_types[0]->name
            ]
        ]);
    }
}
