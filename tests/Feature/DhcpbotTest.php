<?php

namespace Tests\Feature;

use Storage;
use App\User;
use App\Subnet;
use Tests\TestCase;
use App\DhcpSharedNetwork;
use App\Events\SubnetWasCreated;
use App\GoldAccess\Dhcp\DhcpBot;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DhcpbotTest extends TestCase
{
    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_has_a_bio()
    {
        $this->assertEquals('I am Dhcpbot', app('dhcpbot')->bio);
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_a_dhcp_subnet_definition_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));

        // \Storage::disk('dhcp_origins_testing')->assertExists($this->whereTheOriginFileLives($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_definition'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_a_dhcp_subnet_option43_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_option43'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_and_deploy_a_dhcp_subnet_definition_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_definition'));

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->deploy($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_configs_testing')->assertExists(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_definition'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_and_deploy_a_dhcp_subnet_option43_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_option43'));

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->deploy($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_configs_testing')->assertExists(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_option43'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_deploy_and_undeploy_a_dhcp_subnet_definition_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_definition'));

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->deploy($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_configs_testing')->assertExists(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->undeploy($subnet, 'dhcp_subnet_definition');

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_configs_testing')->assertMissing(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_definition'));
        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_definition'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_deploy_and_undeploy_a_dhcp_subnet_option43_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_option43'));

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->deploy($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_configs_testing')->assertExists(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->undeploy($subnet, 'dhcp_subnet_option43');

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_configs_testing')->assertMissing(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_option43'));
        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_option43'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_deploy_and_destroy_a_dhcp_subnet_definition_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_definition'));

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->deploy($subnet, 'dhcp_subnet_definition');

        $this->assertTrue(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_configs_testing')->assertExists(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_definition'));

        app('dhcpbot')->destroy($subnet, 'dhcp_subnet_definition');

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_definition'));
        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_configs_testing')->assertMissing(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_definition'));
        \Storage::disk('dhcp_origins_testing')->assertMissing(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_definition'));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_will_create_deploy_and_destroy_a_dhcp_subnet_option43_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->build($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_origins_testing')->assertExists(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_option43'));

        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->deploy($subnet, 'dhcp_subnet_option43');

        $this->assertTrue(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_configs_testing')->assertExists(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_option43'));

        app('dhcpbot')->destroy($subnet, 'dhcp_subnet_option43');

        $this->assertFalse(app('dhcpbot')->isBuilt($subnet, 'dhcp_subnet_option43'));
        $this->assertFalse(app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_configs_testing')->assertMissing(app('dhcpbot')->getDeployPath($subnet, 'dhcp_subnet_option43'));
        \Storage::disk('dhcp_origins_testing')->assertMissing(app('dhcpbot')->getOriginPath($subnet, 'dhcp_subnet_option43'));
    }
}
