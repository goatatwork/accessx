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
     * The Medialibrary media collection to store these in
     * @var string
     */
    public $media_collection = 'dhcp_files';

    /**
     * Name of the disk configured in filesystems.php
     * @var string
     */
    public $media_disk;

    /**
     * dhcp_temp disk for writing files before adding them to media library
     * @var Storage
     */
    public $temp_storage;

    public function __construct()
    {
        $this->temp_storage = Storage::disk('dhcp_temp');
        $this->media_disk = (env('APP_ENV') == 'testing') ? 'dhcp_configs_test' : 'dhcp_configs';
    }

    /**
     * @param  Subnet $subnet
     * @return boolean
     */
    public function fileExists(Subnet $subnet)
    {
        if ( ! $subnet->getFirstMedia($this->media_collection) ) {
            return false;
        }

        return true;
    }

    /**
     * @param  Subnet $subnet
     * @return boolean
     */
    public function option43FileExists(Subnet $subnet)
    {
        if ( ! $subnet->media()->whereFileName($this->getOption43Filename($subnet))->first() ) {
            return false;
        }

        return true;
    }

    /**
     * @param  Subnet $subnet
     * @return void
     */
    public function deleteFile(Subnet $subnet)
    {
        $subnet->getFirstMedia($this->media_collection)->delete();
    }

    /**
     * @param  Subnet $subnet
     * @return void
     */
    public function deleteOption43File(Subnet $subnet)
    {
        $subnet->media()->whereFileName($this->getOption43Filename($subnet))->first()->delete();
    }

    /**
     * @param  Subnet $subnet
     * @return Spatie\MediaLibrary\Models\Media
     */
    // public function getDhcpFile(Subnet $subnet)
    // {
    //     return $subnet->getFirstMedia($this->media_collection);
    // }

    /**
     * @param  Subnet $subnet
     * @return Spatie\MediaLibrary\Models\Media
     */
    public function writeFile(Subnet $subnet)
    {
        return $subnet->addMedia($this->commitFileToTempStorage($subnet))
            ->toMediaCollection($this->media_collection, $this->media_disk);
    }

    /**
     * @param  Subnet $subnet
     * @return Spatie\MediaLibrary\Models\Media
     */
    public function writeOption43File(Subnet $subnet)
    {
        return $subnet->addMedia($this->commitFileToTempStorage($subnet, 'option43'))
            ->toMediaCollection($this->media_collection, $this->media_disk);
    }

    /**
     * @param  Subnet $subnet
     * @param  string $type default|option43
     * @return string|false String will be disk path to file for addMedia() to use
     */
    public function commitFileToTempStorage(Subnet $subnet, $type = 'default')
    {
        if ($type == 'default') {
            $file_name = $this->getDhcpFilename($subnet);
            $file_content = $this->generateFileContent($subnet);
            $path = $this->temp_storage->path($file_name);

            if ( ! $this->temp_storage->put($file_name, $file_content) ) {
                return false;
            }

            return $path;
        }

        if ($type == 'option43') {
            $file_name = $this->getOption43Filename($subnet);
            $file_content = $this->generateOption43Content($subnet);
            $path = $this->temp_storage->path($file_name);

            if ( ! $this->temp_storage->put($file_name, $file_content) ) {
                return false;
            }

            return $path;
        }
    }

    /**
     * @param  Subnet $subnet
     * @return string
     */
    public function generateFileContent(Subnet $subnet)
    {
        $name = $subnet->dhcp_shared_network->name;
        $vlan = $subnet->dhcp_shared_network->vlan ?: 'none';
        $dnsmasq_tag = $this->getDnsmasqTag($subnet);

        return '# DHCP Range For ' . $name . ' On VLAN ' . $vlan . "\n" .
            'dhcp-range=set:"'.$dnsmasq_tag.'",' . $this->rangeFor($subnet) . ',1h' . "\n" .
            'dhcp-option=tag:"'.$dnsmasq_tag.'",3,'.$subnet->routers;
    }

    /**
     * @param  Subnet $subnet
     * @return string
     */
    public function generateOption43Content(Subnet $subnet)
    {

        return Option43::make($this->getDnsmasqTag($subnet), config('goldaccess.settings.acs_url'));

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
     * Generate the filename
     * @param  Subnet $subnet
     * @return string
     */
    public function getDhcpFilename(Subnet $subnet)
    {
        return $this->getDnsmasqTag($subnet) . '.conf';
    }

    /**
     * Generate the filename
     * @param  Subnet $subnet
     * @return string
     */
    public function getOption43Filename(Subnet $subnet)
    {
        return $this->getDnsmasqTag($subnet) . '-option43.conf';
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
