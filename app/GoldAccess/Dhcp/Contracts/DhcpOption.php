<?php

namespace App\GoldAccess\Dhcp\Contracts;

use App\Subnet;

abstract class DhcpOption
{
    /**
     * Create the dhcp option content
     * @param  string $dnsmasq_tag shared-network-IP_ADD_RE_SS
     * @param  string $value
     * @return string
     */
    abstract public static function make(Subnet $subnet);

    /**
     * Get the filename to write to for this option
     * @param  \App\Subnet $subnet
     * @return string
     */
    abstract public static function getFilename(Subnet $subnet);

    protected static function subnetSlug(Subnet $subnet)
    {
        return $subnet->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $subnet->network_address . '_' . $subnet->cidr);
    }
}
