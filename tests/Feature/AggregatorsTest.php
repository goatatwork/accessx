<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Aggregator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AggregatorsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_agg_index_works()
    {
        $aggregators = factory(Aggregator::class, 3)->create();

        $response = $this->actingAs($this->user)->get('/infrastructure/aggregators');

        $aggregators->each(function($aggregator, $index) use ($response) {
            $response->assertSee($aggregator->name);
        });
    }

    public function test_ping_the_agg_create_page()
    {
        $response = $this->actingAs($this->user)->get('/infrastructure/aggregators/create');

        $response->assertSee('Add An Aggregator');
    }

    public function test_agg_create_form_works()
    {
        $aggregator = factory(Aggregator::class)->make();

        $response = $this->actingAs($this->user)->post('/infrastructure/aggregators', $aggregator->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('aggregators', ['name' => $aggregator->name, 'management_mac' => $aggregator->management_mac]);
    }

    public function test_ping_agg_edit_form()
    {
        $aggregator = factory(Aggregator::class)->create();

        $response = $this->actingAs($this->user)->get('/infrastructure/aggregators/'.$aggregator->id.'/edit');

        $response->assertStatus(200);

        $response->assertSee('Edit '.$aggregator->name);
    }

    public function test_agg_edit_form_works()
    {
        $aggregator = factory(Aggregator::class)->create();
        $new_aggregator = factory(Aggregator::class)->make(['platform_id' => $aggregator->platform_id]);

        $response = $this->actingAs($this->user)->patch('/infrastructure/aggregators/'.$aggregator->id, $new_aggregator->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('aggregators', ['id' => $aggregator->id, 'name' => $new_aggregator->name]);
    }

    public function test_ping_agg_show_page()
    {
        $aggregator = factory(Aggregator::class)->create();

        $response = $this->actingAs($this->user)->get('/infrastructure/aggregators/'.$aggregator->id);

        $response->assertStatus(200);

        $response->assertSee($aggregator->name);
    }

    public function test_agg_delete_form_works()
    {
        $aggregator = factory(Aggregator::class)->create();

        $this->assertDatabaseHas('aggregators', ['id' => $aggregator->id, 'name' => $aggregator->name]);

        $response = $this->actingAs($this->user)->delete('/infrastructure/aggregators/'.$aggregator->id);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('aggregators', ['id' => $aggregator->id, 'name' => $aggregator->name]);
    }
}
