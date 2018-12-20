<?php

namespace App\GoldAccess\Dhcp;

use Storage;
use App\Subnet;
use App\DhcpSharedNetwork;
use App\GoldAccess\Dhcp\Options\Option43;

class Dhcpbot
{
    /**
     * @var string
     */
    public $bio = 'I am Dhcpbot';

    /**
     * Storage object
     * @var Storage
     */
    public $storage;

    /**
     * dhcp_temp disk for writing files before adding them to media library
     * @var Storage
     */
    public $temp_storage;

    /**
     * Prepended to the filename relative to the 'dhcp_configs' filesystem
     * @var string
     */
    public $file_location_prefix = 'dnsmasq.d/';

    public function __construct()
    {
        $this->storage = (env('APP_ENV') == 'testing') ? Storage::disk('dhcp_configs_test') : Storage::disk('dhcp_configs');
        $this->temp_storage = Storage::disk('dhcp_temp');
    }

    public function addOption43toSubnet(Subnet $subnet)
    {
        $content = $this->fileContent($subnet) . "\n" . Option43::make($this->getDnsmasqTag($subnet), config('goldaccess.settings.acs_url'));

        $path = $this->temp_storage->path($this->getDhcpFilename($subnet));

        $this->temp_storage->put($this->getDhcpFileName($subnet), $content);

        $subnet->addMedia($path)->toMediaCollection('subnets', 'dhcp_configs');

        return true;
    }

    /**
     * @param  Subnet $subnet
     * @return boolean
     */
    public function deleteFile(Subnet $subnet)
    {
        return $this->storage->delete($this->getDhcpFileNameWithPath($subnet));
    }

    /**
     * @param  Subnet $subnet
     * @return boolean
     */
    public function writeFile(Subnet $subnet)
    {
        return $this->storage->put($this->getDhcpFileNameWithPath($subnet), $this->fileContent($subnet));
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
        return $this->storage->exists($this->getDhcpFileNameWithPath($subnet));
    }

    /**
     * Generate the filename
     * @param  Subnet $subnet
     * @return string
     */
    public function getDhcpFileName(Subnet $subnet)
    {
        return $subnet->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $subnet->network_address) . '.conf';
    }

    /**
     * Generate the filename
     * @param  Subnet $subnet
     * @return string
     */
    public function getDhcpFileNameWithPath(Subnet $subnet)
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
