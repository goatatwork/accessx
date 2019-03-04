<?php

namespace App\GoldAccess\Dhcp\Options;

use App\Subnet;
use App\GoldAccess\Dhcp\Contracts\Deployable;
use App\GoldAccess\Dhcp\Contracts\DhcpOption;

class Option0 extends DhcpOption
{
    /**
     * Create the content that will be used in the dnsmasq config file
     * @param  App\Subnet $subnet
     * @return string
     */
    public static function make(Deployable $deployable)
    {
        $name = $deployable->dhcp_shared_network->name;
        $vlan = $deployable->dhcp_shared_network->vlan ?: 'none';
        $dnsmasq_tag = self::subnetSlug($deployable);

        return '# DHCP Range For ' . $name . ' On VLAN ' . $vlan . "\n" .
            'dhcp-range=set:"'.$dnsmasq_tag.'",' . (new self)->rangeFor($deployable) . ',1h' . "\n" .
            'tag-if=set:internet-pool,tag:' . $dnsmasq_tag . "\n" .
            'dhcp-option=tag:"'.$dnsmasq_tag.'",3,'.$deployable->routers. "\n" .
            'dhcp-option=tag:"' . $dnsmasq_tag . '",1,' . $deployable->subnet_mask . "\n" .
            'dhcp-option=tag:"' . $dnsmasq_tag . '",5,' . $deployable->dns_servers . "\n" .
            'dhcp-option=tag:"' . $dnsmasq_tag . '",6,' . $deployable->dns_servers . "\n";
    }

    /**
     * Get the filename to write to for this option
     * @param  \App\Subnet $subnet
     * @return string
     */
    public static function getFilename(Deployable $deployable)
    {
        return self::subnetSlug($deployable) . '.conf';
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
