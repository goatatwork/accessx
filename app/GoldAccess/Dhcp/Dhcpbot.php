<?php

namespace App\GoldAccess\Dhcp;

use Storage;
use App\Subnet;
use App\DhcpSharedNetwork;

class Dhcpbot
{
    /**
     * Storage object
     * @var Storage
     */
    public $storage;

    /**
     * Prepended to the filename
     * @var string
     */
    public $file_location_prefix = 'dnsmasq.d/';

    public function __construct()
    {
        $this->storage = (env('APP_ENV') == 'testing') ? Storage::disk('dhcp_configs_test') : Storage::disk('dhcp_configs');
    }

    /**
     * @var string
     */
    public $bio = 'I am Dhcpbot';

    /**
     * @param  Subnet $subnet
     * @return boolean
     */
    public function deleteFile(Subnet $subnet)
    {
        return $this->storage->delete($this->getDhcpFilename($subnet));
    }

    /**
     * @param  Subnet $subnet
     * @return boolean
     */
    public function writeFile(Subnet $subnet)
    {
        return $this->storage->put($this->getDhcpFilename($subnet), $this->fileContent($subnet));
    }

    /**
     * Complete contents of file
     * @param  Subnet $subnet
     * @return string
     */
    public function fileContent(Subnet $subnet)
    {
        $name = $subnet->dhcp_shared_network->name;
        $vlan = $subnet->dhcp_shared_network->vlan ?: 'none';
        $dnsmasq_tag = $this->getDnsmasqTag($subnet);

        return '# DHCP Range For ' . $name . ' On VLAN ' . $vlan . "\n" .
            'dhcp-range=set:"'.$dnsmasq_tag.'",' . $this->rangeFor($subnet) . ',1h' . "\n" .
            'dhcp-option=tag:"'.$dnsmasq_tag.'",3,'.$subnet->routers;
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

    /**
     * Check the existence of the config file
     * @param  Subnet $subnet
     * @return boolean
     */
    public function fileExists(Subnet $subnet)
    {
        return $this->storage->exists($this->getDhcpFilename($subnet));
    }

    /**
     * Generate the filename
     * @param  Subnet $subnet
     * @return string
     */
    public function getDhcpFilename(Subnet $subnet)
    {
        return $this->file_location_prefix . $subnet->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $subnet->network_address) . '.conf';
    }

    /**
     * Generate the filename
     * @param  Subnet $subnet
     * @return string
     */
    public function getDnsmasqTag(Subnet $subnet)
    {
        return $subnet->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $subnet->network_address);
    }
}
