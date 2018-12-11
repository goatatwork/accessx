<?php

namespace App\GoldAccess\Utilities;

use Leth\IPAddress\IP, Leth\IPAddress\IPv4, Leth\IPAddress\IPv6;

class SubnetCalculator
{
    /**
     * @param text
     */
    public $cidr;

    /**
     * @param
     */
    public $gateway_preference;

    /**
     * @param text
     */
    public $ip;

    /**
     * @var IP\NetworkAddress
     */
    protected $network_object;

    public function __construct($ip, $cidr = 24, $gateway_preference = 'top')
    {
        $this->ip = $ip;
        $this->cidr = $cidr;
        $this->gateway_preference = $gateway_preference;
        $this->network_object = IPv4\NetworkAddress::factory($this->ip, $this->cidr);
    }

    /**
     * @return array
     */
    public function calculate()
    {
        $calculations = [
            'comment' => '',
            'gateway_setting' => $this->gateway_preference,
            'ipaddress' => $this->getAddress(),
            'cidr' => $this->getCidr(),
            'subnet_mask' => $this->getSubnetMask(),
            'network_address' => $this->getNetworkAddress(),
            'start_ip' => $this->getStartIp(),
            'start_ip_last_octet' => $this->getStartIpLastOctet(),
            'end_ip' => $this->getEndIp(),
            'end_ip_last_octet' => $this->getEndIpLastOctet(),
            'broadcast_address' => $this->getBroadcastAddress(),
            'routers' => $this->getRouter(),
            'usable_addresses' => $this->getAddressCount(),
            'addresses' => $this->getAllTheAddresses(),
            'default_lease_time' => '3600',
            'max_lease_time' => '3601',
            'dns_servers' => '8.8.8.8',
            'dns_1' => '8.8.8.8',
            'dns_2' => '8.8.4.4',
            'dns_3' => '',
            'dns_4' => '',
        ];
        return $calculations;
    }

    public function getAllTheAddresses()
    {
        $addresses = [];

        foreach ($this->network_object as $ip) {
            array_push($addresses, $ip->__toString());
        }

        array_shift($addresses);
        array_pop($addresses);

        $this->gateway_preference == 'top' ? array_pop($addresses) : array_shift($addresses);

        return $addresses;
    }

    /**
     * @return string The IP
     */
    protected function getAddress()
    {
        return $this->network_object->get_address()->__toString();
    }

    /**
     * @return integer Number of usable IPs
     */
    protected function getAddressCount()
    {
        return $this->network_object->count() - 3;
    }

    /**
     * @return string The broadcast address
     */
    protected function getBroadcastAddress()
    {
        return $this->network_object->get_broadcast_address()->__toString();
    }

    /**
     * @return string The CIDR
     */
    protected function getCidr()
    {
        return $this->network_object->get_cidr();
    }

    /**
     * @return string The last usable IP
     */
    protected function getEndIp()
    {
        return $this->gateway_preference == 'top' ? $this->network_object->get_address_in_network(-2)->__toString() : $this->network_object->get_address_in_network(-1)->__toString();
    }

    /**
     * @return string The last usable IP
     */
    protected function getEndIpLastOctet()
    {
        return $this->gateway_preference == 'top' ? $this->network_object->get_address_in_network(-2)->get_octet(-1) : $this->network_object->get_address_in_network(-1)->get_octet(-1);
    }

    /**
     * @return string The network address
     */
    protected function getNetworkAddress()
    {
        return $this->network_object->get_NetworkAddress()->__toString();
    }

    /**
     * @return string The IP of the router
     */
    protected function getRouter()
    {
        return $this->gateway_preference == 'top' ? $this->network_object->get_address_in_network(-1)->__toString() : $this->network_object->get_address_in_network(1)->__toString();
    }

    /**
     * @return string The first usable IP
     */
    protected function getStartIp()
    {
        return $this->gateway_preference == 'top' ? $this->network_object->get_address_in_network(1)->__toString() : $this->network_object->get_address_in_network(2)->__toString();
    }

    protected function getStartIpLastOctet()
    {
        return $this->gateway_preference == 'top' ? $this->network_object->get_address_in_network(1)->get_octet(-1) : $this->network_object->get_address_in_network(2)->get_octet(-1);
    }

    /**
     * @return string The subnet mask
     */
    protected function getSubnetMask()
    {
        return $this->network_object->get_subnet_mask()->__toString();
    }
}
