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
    public function test_bot_can_generate_the_filename_for_the_dnsmasq_config_file_for_a_subnet()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.1.2.0']);

        $expected_string = $subnet->dhcp_shared_network->slug . '-10_1_2_0.conf';

        $this->assertEquals($expected_string, app('dhcpbot')->getDhcpFilename($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_can_generate_the_filename_for_the_dnsmasq_option43_file_for_a_subnet()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.1.2.0']);

        $expected_string = $subnet->dhcp_shared_network->slug . '-10_1_2_0-option43.conf';

        $this->assertEquals($expected_string, app('dhcpbot')->getOption43Filename($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function given_a_subnet_model_the_bot_can_create_and_write_dnsmasq_dhcp_config_files_in_the_directory_that_dnsmasq_needs_them_in()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $newfile = app('dhcpbot')->writeFile($subnet);

        $this->assertEquals($newfile->file_name, $subnet->dhcp_shared_network->slug.'-10_2_3_0.conf');

        \Storage::disk('dhcp_configs_test')->assertExists('dnsmasq.d/'.$newfile->file_name);

        $subnet_again = Subnet::find($subnet->id);

        $this->assertTrue(app('dhcpbot')->fileExists($subnet_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function given_a_subnet_model_the_bot_can_create_and_write_dnsmasq_option43_files_in_the_directory_that_dnsmasq_needs_them_in()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $newfile = app('dhcpbot')->writeOption43File($subnet);

        $this->assertEquals($newfile->file_name, $subnet->dhcp_shared_network->slug.'-10_2_3_0-option43.conf');

        \Storage::disk('dhcp_configs_test')->assertExists('dnsmasq.d/'.$newfile->file_name);

        $subnet_again = Subnet::find($subnet->id);

        $this->assertTrue(app('dhcpbot')->option43FileExists($subnet_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function given_a_subnet_model_the_bot_can_create_write_and_delete_dnsmasq_dhcp_config_files_in_the_directory_that_dnsmasq_needs_them_in()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $newfile = app('dhcpbot')->writeFile($subnet);

        $this->assertEquals($newfile->file_name, $subnet->dhcp_shared_network->slug.'-10_2_3_0.conf');

        \Storage::disk('dhcp_configs_test')->assertExists('dnsmasq.d/'.$newfile->file_name);

        $subnet_again = Subnet::find($subnet->id);

        $this->assertTrue(app('dhcpbot')->fileExists($subnet_again));

        app('dhcpbot')->deleteFile($subnet_again);

        $subnet_again_again = Subnet::find($subnet->id);

        $this->assertFalse(app('dhcpbot')->fileExists($subnet_again_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function given_a_subnet_model_the_bot_can_create_write_and_delete_dnsmasq_option43_files_in_the_directory_that_dnsmasq_needs_them_in()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $newfile = app('dhcpbot')->writeOption43File($subnet);

        $this->assertEquals($newfile->file_name, $subnet->dhcp_shared_network->slug.'-10_2_3_0-option43.conf');

        \Storage::disk('dhcp_configs_test')->assertExists('dnsmasq.d/'.$newfile->file_name);

        $subnet_again = Subnet::find($subnet->id);

        $this->assertTrue(app('dhcpbot')->option43FileExists($subnet_again));

        app('dhcpbot')->deleteOption43File($subnet_again);

        $subnet_again_again = Subnet::find($subnet->id);

        $this->assertFalse(app('dhcpbot')->option43FileExists($subnet_again_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_has_a_fileExists_method_that_reports_correctly_when_the_real_dnsmasq_file_exists()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));

        $newfile = app('dhcpbot')->writeFile($subnet);

        $this->assertEquals($newfile->file_name, $subnet->dhcp_shared_network->slug.'-10_2_3_0.conf');

        \Storage::disk('dhcp_configs_test')->assertExists('dnsmasq.d/'.$newfile->file_name);

        $subnet_again = Subnet::find($subnet->id);

        $this->assertTrue(app('dhcpbot')->fileExists($subnet_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_has_a_fileExists_method_that_reports_correctly_when_the_real_dnsmasq_option43_file_exists()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.2.3.0']);

        $this->assertFalse(app('dhcpbot')->option43FileExists($subnet));

        $newfile = app('dhcpbot')->writeOption43File($subnet);

        $this->assertEquals($newfile->file_name, $subnet->dhcp_shared_network->slug.'-10_2_3_0-option43.conf');

        \Storage::disk('dhcp_configs_test')->assertExists('dnsmasq.d/'.$newfile->file_name);

        $subnet_again = Subnet::find($subnet->id);

        $this->assertTrue(app('dhcpbot')->option43FileExists($subnet_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_has_a_fileExists_method_that_reports_correctly_when_the_real_dnsmasq_file_does_not_exist()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.3.4.0']);

        // $newfile = app('dhcpbot')->writeFile($subnet);

        \Storage::disk('dhcp_configs_test')->assertMissing('dnsmasq.d/'.$subnet->dhcp_shared_network->slug.'-10_3_4_0.conf');

        $subnet_again = Subnet::find($subnet->id);

        $this->assertFalse(app('dhcpbot')->fileExists($subnet_again));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function bot_has_a_fileExists_method_that_reports_correctly_when_the_real_dnsmasq_option43_file_does_not_exist()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.3.4.0']);

        // $newfile = app('dhcpbot')->writeFile($subnet);

        \Storage::disk('dhcp_configs_test')->assertMissing('dnsmasq.d/'.$subnet->dhcp_shared_network->slug.'-10_3_4_0-option43.conf');

        $subnet_again = Subnet::find($subnet->id);

        $this->assertFalse(app('dhcpbot')->option43FileExists($subnet_again));
    }
}
