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
 *
 * These tests also test the exact same thing as DnsmasqServerTest, except
 * these use the app('dhcp') service instead of dealing directly with the
 * models.
 */

class DhcpServiceProviderTest extends TestCase
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

        $this->assertTrue(app('dhcp')->isUp());
    }

    public function Ttest_it_can_tell_if_a_server_is_down()
    {
        config(['goldaccess.dhcp.ip' => '10.0.0.11']);

        $this->assertFalse(app('dhcp')->isUp());
    }

    public function Ttest_it_can_tell_if_the_dnsmasq_service_is_running()
    {
        $this->assertTrue(app('dhcp')->isRunning());
    }

    public function Ttest_it_can_tell_if_the_dnsmasq_service_is_not_running()
    {
        config(['goldaccess.dhcp.pid' => '/run/dnsmasq/dnsmasq1.pid']);

        $this->assertFalse(app('dhcp')->isRunning());
    }

    public function Ttest_it_can_deploy_config_file_to_localhost()
    {

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $record = app('dhcp')->config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue(app('dhcp')->config_file->checkOption82StaticIpFor($provisioning_record));
        $this->assertFileExists($provisioning_record->file->getPath());

    }

    public function Ttest_it_can_deploy_config_file_to_remote_server()
    {

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $record = app('dhcp')->config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue(app('dhcp')->config_file->checkOption82StaticIpFor($provisioning_record));
        // $this->assertFileExists($provisioning_record->file->getPath());

        // app('dhcp')->config_file->uploadOption82StaticIpFor($provisioning_record);

        $this->assertTrue(app('dhcp')->config_file->checkRemoteOption82StaticIpFor($provisioning_record));

    }

    public function Ttest_it_can_undeploy_config_file_to_remote_server()
    {

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $record = app('dhcp')->config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue(app('dhcp')->config_file->checkOption82StaticIpFor($provisioning_record));
        $this->assertFileExists($provisioning_record->file->getPath());

        app('dhcp')->config_file->uploadOption82StaticIpFor($provisioning_record);

        $this->assertTrue(app('dhcp')->config_file->checkRemoteOption82StaticIpFor($provisioning_record));

        app('dhcp')->config_file->removeRemoteOption82StaticIpFor($provisioning_record);

        $this->assertFalse(app('dhcp')->config_file->checkRemoteOption82StaticIpFor($provisioning_record));
    }
}
