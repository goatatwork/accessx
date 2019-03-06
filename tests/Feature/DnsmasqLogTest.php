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

    /**
     * @group dnsmasqlog
     * @test
     */
    public function test_api_will_accept_json_data_to_create_logs()
    {

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

    protected function jsonString()
    {
        return "{
            ACTION: 'old',
            HOSTMAC:'00:02:71:40:b8:aa',
            IP:'192.168.127.2',
            HOSTNAME:'none',
            DNSMASQ_DOMAIN:'none',
            DNSMASQ_SUPPLIED_HOSTNAME:'none',
            DNSMASQ_TIME_REMAINING:'600',
            DNSMASQ_OLD_HOSTNAME:'none',
            DNSMASQ_INTERFACE:'br0',
            DNSMASQ_RELAY_ADDRESS:'192.168.127.254',
            DNSMASQ_TAGS:'basementstack/1/1/1 br0',
            DNSMASQ_LOG_DHCP:'1',
            DNSMASQ_CLIENT_ID:'00:33:30:34:32:34:31:35:37',
            DNSMASQ_CIRCUIT_ID:'none',
            DNSMASQ_SUBSCRIBER_ID:'basementstack/1/1/1',
            DNSMASQ_REMOTE_ID:'none',
            DNSMASQ_VENDOR_CLASS:'GE-2426A-0GF',
            DNSMASQ_REQUESTED_OPTIONS:'none'
        }";
    }
}
