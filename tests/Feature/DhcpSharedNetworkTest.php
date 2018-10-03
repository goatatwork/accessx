<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ProvisioningRecord;
use App\DhcpSharedNetwork;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DhcpSharedNetworkTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @return void
     */
    public function test_api_can_create_dhcp_shared_network()
    {
        $sn = factory(DhcpSharedNetwork::class)->make();

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/dhcp/dhcp_shared_networks', $sn->toArray());

        $response->assertJson([
            'name' => $sn->name
        ]);

        $this->assertDatabaseHas('dhcp_shared_networks', ['name' => $sn->name]);
    }

    /**
     * @return void
     */
    public function test_api_can_edit_shared_networks()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();

        $sn_newdata = factory(DhcpSharedNetwork::class)->make();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/dhcp/dhcp_shared_networks/' . $sn->id, $sn_newdata->toArray());

        $response->assertJson([
            'id' => $sn->id,
            'name' => $sn_newdata->name
        ]);

        $this->assertDatabaseHas('dhcp_shared_networks', ['id' => $sn->id, 'name' => $sn_newdata->name]);
    }

    /**
     * @return void
     */
    public function test_api_can_delete_shared_networks()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();

        $this->assertDatabaseHas('dhcp_shared_networks', [
            'id' => $sn->id
        ]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/dhcp/dhcp_shared_networks/' . $sn->id);

        $this->assertDatabaseMissing('dhcp_shared_networks', [
            'id' => $sn->id
        ]);
    }

    /**
     * @return  void
     */
    public function test_non_api_can_show_shared_networks_on_index_page()
    {
        $shared_networks = factory(DhcpSharedNetwork::class, 4)->create();

        $response  = $this->actingAs($this->user)->get('/dhcp');

        $shared_networks->each(function($shared_network, $index) use ($response) {
            $response->assertSee($shared_network->name);
        });
    }

    /**
     * @return  void
     */
    public function test_ping_shared_networks_create_page()
    {
        $response  = $this->actingAs($this->user)->get('/dhcp/shared_networks/create');

        $response->assertSee('Add A Shared Network');
    }

    /**
     * @return  void
     */
    public function test_ping_shared_networks_edit_page()
    {
        $shared_network = factory(DhcpSharedNetwork::class)->create(['notes' => '']);

        $response  = $this->actingAs($this->user)->get('/dhcp/shared_networks/'.$shared_network->id.'/edit');

        $response->assertSee('Edit '.$shared_network->name);
    }

    /**
     * @return  void
     */
    public function test_non_api_form_can_add_shared_networks()
    {
        $shared_network = factory(DhcpSharedNetwork::class)->make();

        $this->assertDatabaseMissing('dhcp_shared_networks', ['name' => $shared_network->name]);

        $response  = $this->actingAs($this->user)->post('/dhcp/shared_networks', $shared_network->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('dhcp_shared_networks', ['name' => $shared_network->name]);
    }

    /**
     * @return  void
     */
    public function test_non_api_form_can_add_management_shared_networks()
    {
        $shared_network = factory(DhcpSharedNetwork::class)->make(['management' => '1']);

        $this->assertDatabaseMissing('dhcp_shared_networks', ['name' => $shared_network->name]);

        $response  = $this->actingAs($this->user)->post('/dhcp/shared_networks', $shared_network->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('dhcp_shared_networks', ['name' => $shared_network->name, 'management' => true]);
    }

    /**
     * @return  void
     */
    public function test_non_api_form_can_edit_shared_networks()
    {
        $shared_network = factory(DhcpSharedNetwork::class)->create();
        $new_shared_network = factory(DhcpSharedNetwork::class)->make();

        $this->assertDatabaseMissing('dhcp_shared_networks', ['id' => $shared_network->id, 'name' => $new_shared_network->name]);

        $response  = $this->actingAs($this->user)->patch('/dhcp/shared_networks/'.$shared_network->id, $new_shared_network->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('dhcp_shared_networks', ['id' => $shared_network->id, 'name' => $new_shared_network->name]);
    }

    /**
     * @return  void
     */
    public function test_non_api_form_can_delete_shared_networks()
    {
        $shared_network = factory(DhcpSharedNetwork::class)->create();

        $this->assertDatabaseHas('dhcp_shared_networks', ['id' => $shared_network->id, 'name' => $shared_network->name]);

        $response  = $this->actingAs($this->user)->delete('/dhcp/shared_networks/'.$shared_network->id);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('dhcp_shared_networks', ['id' => $shared_network->id, 'name' => $shared_network->name]);
    }

    public function test_sharednetwork_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $sn = $provrec->ip_address->subnet->dhcp_shared_network;

        $this->assertTrue($sn->has_provisioning_records);
    }

    public function test_sharednetwork_knows_if_it_does_not_have_provisioning_records()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();

        $this->assertFalse($sn->has_provisioning_records);
    }
}
