<?php

namespace App\GoldAccess\Dhcp\Options;

use App\Subnet;
use App\GoldAccess\Dhcp\Contracts\DhcpOption;

class Option0 extends DhcpOption
{
    /**
     * Create the content that will be used in the dnsmasq config file
     * @param  string $dnsmasq_tag
     * @param  string $value
     * @return string
     */
    public static function make(Subnet $subnet)
    {
        $name = $subnet->dhcp_shared_network->name;
        $vlan = $subnet->dhcp_shared_network->vlan ?: 'none';
        $dnsmasq_tag = self::subnetSlug($subnet);

        return '# DHCP Range For ' . $name . ' On VLAN ' . $vlan . "\n" .
            'dhcp-range=set:"'.$dnsmasq_tag.'",' . (new self)->rangeFor($subnet) . ',1h' . "\n" .
            'dhcp-option=tag:"'.$dnsmasq_tag.'",3,'.$subnet->routers;
    }

    /**
     * Get the filename to write to for this option
     * @param  \App\Subnet $subnet
     * @return string
     */
    public static function getFilename(Subnet $subnet)
    {
        return self::subnetSlug($subnet) . '.conf';
    }

    /**
     * Dnsmasq range string
     * @param  Subnet $subnet
     * @return string
     */
    public function rangeFor(Subnet $subnet)
    {
        return $subnet->start_ip . ',' . $subnet->end_ip . ',' . $subnet->subnet_mask;
    }
}
