<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\ProvisioningRecord;
use App\GoldAccess\Dhcp\DnsmasqServer;
use Illuminate\Foundation\Testing\WithFaker;
use App\GoldAccess\Dhcp\DnsmasqConfigFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * These tests hit a real ssh endpoint/server. These tests will fail if the
 * server is not accessible.
 */

class DnsmasqServerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
    }

    public function test_bs_test()
    {
        $this->assertEquals(1, 1);
    }

    public function Ttest_it_can_tell_if_a_server_is_up()
    {
        config(['remote.connections.dhcp.host' => '10.0.0.10']);
        $server = new DnsmasqServer();
        $this->assertTrue($server->isUp());
    }

    public function Ttest_it_can_tell_if_a_server_is_down()
    {
        config(['goldaccess.dhcp.ip' => '10.0.0.11']);
        $server = new DnsmasqServer();
        $this->assertFalse($server->isUp());
    }

    public function Ttest_it_can_tell_if_the_dnsmasq_service_is_running()
    {
        $server = new DnsmasqServer();
        $this->assertTrue($server->isRunning());
    }

    public function Ttest_it_can_tell_if_the_dnsmasq_service_is_not_running()
    {
        config(['goldaccess.dhcp.pid' => '/run/dnsmasq/dnsmasq1.pid']);
        $server = new DnsmasqServer();
        $this->assertFalse($server->isRunning());
    }

    public function Ttest_it_can_deploy_config_file_to_server()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $record = $config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue($config_file->checkOption82StaticIpFor($provisioning_record));
        $this->assertFileExists($record->file->getPath());
    }

    public function test_dnsmasq_server_test_is_disabled()
    {
        //
    }
}
