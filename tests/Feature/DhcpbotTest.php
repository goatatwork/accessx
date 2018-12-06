<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\GoldAccess\Dhcp\DhcpBot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DhcpbotTest extends TestCase
{
    /**
     * @group dhcpbot
     * @test
     */
    public function test_has_a_bio()
    {
        $this->assertEquals('I am Dhcpbot', app('dhcpbot')->bio);
    }

}
