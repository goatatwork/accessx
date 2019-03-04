<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DockerbotTest extends TestCase
{
    /**
     * @group dockerbot
     * @test
     */
    public function test_dockerbot_will_restart_the_dhcp_container_in_the_goldaccess_config_file()
    {
        // $starting_uptime = app('dockerbot')->containerUptime(config('goldaccess.dockerbot.services.dhcp.container_name'));

        // app('dockerbot')->containerRestart(config('goldaccess.dockerbot.services.dhcp.container_name'));

        // $updated_uptime = app('dockerbot')->containerUptime(config('goldaccess.dockerbot.services.dhcp.container_name'));

        // $this->assertNotEquals($starting_uptime, $updated_uptime);
    }
}
