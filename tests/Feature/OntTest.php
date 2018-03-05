<?php

namespace Tests\Feature;

use App\Ont;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OntTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_the_web_form_can_create_onts()
    {
        $ont = factory(Ont::class)->make();

        $response = $this->actingAs($this->user)->post('/onts', $ont->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('onts', ['model_number' => $ont->model_number]);
    }

    public function test_the_web_form_can_edit_onts()
    {
        $ont = factory(Ont::class)->create();
        $new_ont_info = factory(Ont::class)->make();

        $response = $this->actingAs($this->user)->patch('/onts/'.$ont->id, $new_ont_info->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('onts', ['id' => $ont->id, 'model_number' => $new_ont_info->model_number]);
    }

    public function test_the_web_form_can_delete_onts()
    {
        $ont = factory(Ont::class)->create();

        $response = $this->actingAs($this->user)->delete('/onts/'.$ont->id);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('onts', ['id' => $ont->id]);
    }
}
