<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\SubnetCalculatorForm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubnetCalculatorTest extends TestCase
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
    public function test_api_will_calculate_subnets_with_gateway_on_top()
    {
        $form_data = factory(SubnetCalculatorForm::class)->make(['ip' => '13.13.13.13', 'cidr' => 24, 'gateway_preference' => 'top']);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/subnetcalculator', $form_data->toArray());

        $response->assertJson([
            'comment' => '',
            'network_address' => '13.13.13.0',
            'subnet_mask' => '255.255.255.0',
            'cidr' => 24,
            'start_ip' => '13.13.13.1',
            'end_ip' => '13.13.13.253',
            'routers' => '13.13.13.254',
            'broadcast_address' => '13.13.13.255',
            'dns_servers' => '8.8.8.8',
            'default_lease_time' => '3600',
            'max_lease_time' => '3601',
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_calculate_subnets_with_gateway_on_bottom()
    {
        $form_data = factory(SubnetCalculatorForm::class)->make(['ip' => '13.13.13.13', 'cidr' => 24, 'gateway_preference' => 'bottom']);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/subnetcalculator', $form_data->toArray());

        $response->assertJson([
            'network_address' => '13.13.13.0',
            'start_ip' => '13.13.13.2',
            'end_ip' => '13.13.13.254',
            'routers' => '13.13.13.1'
        ]);
    }
}
