<?php

// UNUSED

namespace App\GoldAccess\Dhcp;

use SSH;
use File;
use Storage;
use App\ProvisioningRecord;

class DnsmasqConfigFile
{
    /**
     * Do everything needed to make a new static IP for management
     *
     * @param ProvisioningRecord $provisioning_record [description]
     * @return  App\ProvisioningRecord
     */
    public function addOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {
        $this->createStaticIpConfigFileFor($provisioning_record)
            ->associateConfigFileWith($provisioning_record)
            ->uploadOption82StaticIpFor($provisioning_record);

        return $provisioning_record;
    }

    /**
     * Remove the medialibrary and file system instances of the config file.
     *
     * @param ProvisioningRecord $provisioning_record [description]
     * @return  $this
     */
    public function removeOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {
        $this->removeRemoteOption82StaticIpFor($provisioning_record);
        $provisioning_record->file->delete();
        return $this;
    }

    /**
     * Get the local and remote status for the config snippet
     * @param  ProvisioningRecord $provisioning_record
     * @return array
     */
    public function statusOfOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {
        return [
            'local' => $this->checkOption82StaticIpFor($provisioning_record),
            'remote' => $this->checkRemoteOption82StaticIpFor($provisioning_record)
        ];
    }

    /**
     * Use Medialibarary to store the config file on the provisioning record.
     * Medialibarary *moves* the file from storage_path() into the folder for
     * the given record.
     *
     * @param ProvisioningRecord $provisioning_record
     * @return  $this
     */
    protected function associateConfigFileWith(ProvisioningRecord $provisioning_record)
    {
        $filename = $provisioning_record->port_tag_unique;

        $path = Storage::path($filename);
        $provisioning_record->addMedia($path)->toMediaCollection('default', 'dhcp_configs');

        return $this;
    }

    /**
     * Check to see if the provisioning record has a config file attached to it
     * and whether or not that file exists.
     *
     * @param ProvisioningRecord $provisioning_record [description]
     * @return  boolean
     */
    public function checkOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {

        return (!is_null($provisioning_record->getFirstMedia())) ? (File::exists($provisioning_record->getFirstMedia()->getPath()) ? true : false) : false;

    }

    /**
     * Check to see if the provisioning record has a config file attached to it
     * and whether or not that file exists on the remote server.
     *
     * @param ProvisioningRecord $provisioning_record [description]
     * @return  boolean
     */
    public function checkRemoteOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {
        $remote_path = config('goldaccess.dhcp.dhcp_config_files_path') . '/' . $provisioning_record->getFirstMedia()->file_name;

        return SSH::into('dhcp')->exists($remote_path);
    }

    /**
     * Create a new config file for the static ip for a provisioning record. It
     * will temporarily land in storage_path().
     *
     * @param  ProvisioningRecord $provisioning_record
     * @return $this
     */
    protected function createStaticIpConfigFileFor(ProvisioningRecord $provisioning_record)
    {
        $filename = $provisioning_record->port_tag_unique;

        foreach ($this->dnsmasqConfigLinesFor($provisioning_record) as $line) {
            Storage::append($filename, $line);
        }

        return $this;
    }

    /**
     * An array containing the dnsmas.conf settings
     *
     * @param  ProvisioningRecord $provisioning_record
     * @return array Each element is a configuration line.
     */
    protected function dnsmasqConfigLinesFor(ProvisioningRecord $provisioning_record)
    {
        $tag = $provisioning_record->port_tag;

        $ip = $provisioning_record->ip_address->address;
        $netmask = $provisioning_record->ip_address->subnet->subnet_mask;
        $gateway = $provisioning_record->ip_address->subnet->routers;
        $broadcast = $provisioning_record->ip_address->subnet->broadcast_address;

        return [
            'dhcp-match=set:'.$tag.',vi-encap:82,6,"'.$tag.'"',
            'dhcp-range='.$tag.','.$ip.','.$ip.','.$netmask.',5m',
            'dhcp-option-force=net:'.$tag.',1,'.$netmask.'',
            'dhcp-option-force=net:'.$tag.',3,'.$gateway.'',
            'dhcp-option-force=net:'.$tag.',6,8.8.8.8',
            'dhcp-option-force=net:'.$tag.',28,'.$broadcast.'',
            'dhcp-option-force=net:'.$tag.',66,10.0.0.10',
            'dhcp-boot=net:'.$tag.',S0301243,10.0.0.10',
        ];
    }

    /**
     * Remove the config snippet file from the remote server
     *
     * @param ProvisioningRecord $provisioning_record [description]
     * @return  boolean
     */
    public function removeRemoteOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {
        $remote_path = config('goldaccess.dhcp.dhcp_config_files_path') . '/' . $provisioning_record->getFirstMedia()->file_name;
        return SSH::into('dhcp')->delete($remote_path);
    }

    /**
     * Upload the config file (snippet) to the server. NOTE: SSH::put returns
     * nothing (@void).
     *
     * @param  ProvisioningRecord $provisioning_record
     * @return  $this
     */
    public function uploadOption82StaticIpFor(ProvisioningRecord $provisioning_record)
    {
        $filename = $provisioning_record->port_tag_unique;

        $path = $provisioning_record->getFirstMedia()->getPath();

        SSH::into('dhcp')->put($path, config('goldaccess.dhcp.dhcp_config_files_path') . '/' . $provisioning_record->file->file_name);

        return $this;
    }
}
