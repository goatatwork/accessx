<?php

namespace Tests\Feature;

use App\Ont;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OntApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @group onts
     * @return void
     */
    public function test_api_will_fetch_onts()
    {
        $onts = factory(Ont::class, 5)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/onts');

        $response->assertJson([
            0 => [
                'model_number' => $onts[0]->model_number,
            ],
            4 => [
                'model_number' => $onts[4]->model_number,
            ]
        ]);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_create_an_ont()
    {
        $ont = factory(Ont::class)->make();

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts', $ont->toArray());

        $response->assertJson([
            'model_number' => $ont->model_number
        ]);
    }

    /**
     * @group onts
     * @return void
     */
    public function test_api_will_fetch_an_ont()
    {
        $ont = factory(Ont::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/onts/' . $ont->id);

        $response->assertJson([
            'model_number' => $ont->model_number
        ]);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_update_an_ont()
    {
        $ont = factory(Ont::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/onts/' . $ont->id, ['model_number' => 'mymodel']);

        $response->assertJson([
            'model_number' => 'mymodel'
        ]);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_delete_ont()
    {
        $ont = factory(Ont::class)->create();

        $this->assertDatabaseHas('onts', ['model_number' => $ont->model_number]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/onts/' . $ont->id);

        $this->assertDatabaseMissing('onts', ['model_number' => $ont->model_number]);
    }
}
