<?php

namespace Tests\Feature;

use App\User;
use App\DnsmasqLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DnsmasqLogTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_api_will_accept_json_data_to_create_logs()
    {
        $log = factory(DnsmasqLog::class)->make();

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/dnsmasq/events', $log->toArray());

        $this->assertDatabaseHas('dnsmasq_logs', [
            'event' => $log
        ]);
    }

    protected function jsonMessageFromDnsmasq()
    {
        return [
            'action' => 'old',
            'hostmac' => '52:54:00:xx:xx:xx',
            'ip' => '192.168.168.1',
            'hostname' => '',
            'DNSMASQ_DOMAIN' => '',
            'DNSMASQ_SUPPLIED_HOSTNAME' => '',
            'DNSMASQ_TIME_REMAINING' => '',
            'DNSMASQ_OLD_HOSTNAME' => '',
            'DNSMASQ_INTERFACE' => '',
            'DNSMASQ_RELAY_ADDRESS' => '',
            'DNSMASQ_TAGS' => '',
            'DNSMASQ_LOG_DHCP' => '',
            'DNSMASQ_CLIENT_ID' => '',
            'DNSMASQ_CIRCUIT_ID' => '',
            'DNSMASQ_SUBSCRIBER_ID' => '',
            'DNSMASQ_REMOTE_ID' => '',
            'DNSMASQ_VENDOR_CLASS' => '',
            'DNSMASQ_REQUESTED_OPTIONS' => '',
        ];
    }
}
