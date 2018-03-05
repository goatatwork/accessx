<?php

namespace Tests\Feature;

use File;
use App\User;
use Tests\TestCase;
use App\ProvisioningRecord;
use Illuminate\Support\Facades\Storage;
use App\GoldAccess\Dhcp\DnsmasqConfigFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DnsmasqConfigFileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        // config()->set('medialibrary.default_filesystem', 'dhcp_configs_test');
    }

    public function test_bs_test()
    {
        $this->assertEquals(1, 1);
    }

    public function Ttest_it_can_output_an_array_of_config_lines()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();
        $tag = $provisioning_record->port_tag;
        $ip = $provisioning_record->ip_address->address;
        $netmask = $provisioning_record->ip_address->subnet->subnet_mask;
        $gateway = $provisioning_record->ip_address->subnet->routers;
        $broadcast = $provisioning_record->ip_address->subnet->broadcast_address;

        $this->assertEquals($config_file->syntaxFor($provisioning_record), [
            'dhcp-match=set:'.$tag.',vi-encap:82,6,"'.$tag.'"',
            'dhcp-range='.$tag.','.$ip.','.$ip.','.$netmask.',5m',
            'dhcp-option-force=net:'.$tag.',1,'.$netmask.'',
            'dhcp-option-force=net:'.$tag.',3,'.$gateway.'',
            'dhcp-option-force=net:'.$tag.',6,8.8.8.8',
            'dhcp-option-force=net:'.$tag.',28,'.$broadcast.'',
            'dhcp-option-force=net:'.$tag.',66,10.0.0.10',
            'dhcp-boot=net:'.$tag.',S0301243,10.0.0.10',
        ]);
    }

    public function Ttest_it_can_create_static_ip_entry_based_on_option82()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $record = $config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue($config_file->statusOfOption82StaticIpFor($provisioning_record)['local']);
        $this->assertFileExists($record->file->getPath());
    }

    public function Ttest_it_can_check_on_the_existence_of_a_static_ip_entry_that_should_return_false_because_it_was_never_created()
    {
        $config_file = new DnsmasqConfigFile();
        $provisioning_record = factory(ProvisioningRecord::class)->create();
        $this->assertFalse($config_file->statusOfOption82StaticIpFor($provisioning_record));
        // $config_file = new DnsmasqConfigFile();

        // $provisioning_record = factory(ProvisioningRecord::class)->create();

        // $this->assertFalse($config_file->statusOfOption82StaticIpFor($provisioning_record)['local']);
    }

    public function Ttest_it_can_check_on_the_existence_of_a_static_ip_entry_that_should_return_true()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue($config_file->statusOfOption82StaticIpFor($provisioning_record)['local']);
    }

    public function Ttest_it_can_check_on_the_existence_of_a_static_ip_entry_that_should_return_false_because_the_file_is_missing()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertFileExists($provisioning_record->file->getPath());
        File::delete($provisioning_record->file->getPath());
        $this->assertFileNotExists($provisioning_record->file->getPath());

        $this->assertFalse($config_file->checkOption82StaticIpFor($provisioning_record));
    }

    /**
     * This test also proves that when a ->getFirstMedia()->delete() is called,
     * the file is removed along with the database entry.
     *
     * @return void
     */
    public function Ttest_it_can_check_on_the_existence_of_a_static_ip_entry_that_should_return_false_because_the_media_object_has_been_deleted()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue($provisioning_record->getFirstMedia()->exists());
        $this->assertFileExists($provisioning_record->file->getPath());
        $provisioning_record->getFirstMedia()->delete();
        $this->assertFalse($provisioning_record->getFirstMedia()->exists());
        $this->assertFileNotExists($provisioning_record->file->getPath());

        $this->assertFalse($config_file->checkOption82StaticIpFor($provisioning_record));
    }

    /**
     * This test also proves that when a ->file->delete() is called,
     * the file is removed along with the database entry.
     *
     * @return void
     */
    public function Ttest_it_can_check_on_the_existence_of_a_static_ip_entry_using_the_appended_file_attribute_that_should_return_false_because_the_media_object_has_been_deleted()
    {
        $config_file = new DnsmasqConfigFile();

        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $config_file->addOption82StaticIpFor($provisioning_record);

        $this->assertTrue($provisioning_record->getFirstMedia()->exists());
        $this->assertFileExists($provisioning_record->file->getPath());
        $provisioning_record->file->delete();
        $this->assertFalse($provisioning_record->getFirstMedia()->exists());
        $this->assertFileNotExists($provisioning_record->file->getPath());

        $this->assertFalse($config_file->checkOption82StaticIpFor($provisioning_record));
    }
}
