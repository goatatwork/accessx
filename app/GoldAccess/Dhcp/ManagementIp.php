<?php

namespace App\GoldAccess\Dhcp;

use Storage;
use App\GaSetting;
use App\ProvisioningRecord;

class ManagementIp
{
    /**
     * @param \App\ProvisioningRecord $provisioning_record
     */
    public $provisioning_record;

    /**
     * @param \Illuminate\Support\Facades\Storage $storage
     */
    public $storage;

    /**
     * The constructor
     */
    public function __construct(ProvisioningRecord $provisioning_record)
    {
        $this->provisioning_record = $provisioning_record;
        $this->storage = (env('APP_ENV') == 'testing') ? Storage::disk('dhcp_configs_testing') : Storage::disk('dhcp_configs');
    }

    public function check()
    {
        return $this->storage->exists($this->filename());
    }

    /**
     * Get the config file snippet
     * @return string
     */
    public function get()
    {
        return ($this->check()) ? $this->storage->get($this->filename()) : false;
    }

    /**
     * Create and then populate the config snippet file
     * @return this
     */
    public function make()
    {
        return $this->createFile()->fillFile();
    }

    /**
     * Remove the config snippet file
     */
    public function remove()
    {
        return $this->storage->delete($this->filename());
    }

    /**
     * A comment line for the top of the config snippet file
     * @return string
     */
    protected function configComment()
    {
        $name = $this->provisioning_record->service_location->customer->customer_name;
        $id = $this->provisioning_record->service_location->customer->id;

        return '## Management IP for '.$name.' (ID:'.$id.')';
    }

    /**
     * Create the file by inserting the first line of it
     * @return this
     */
    protected function createFile()
    {
        $this->storage->put($this->filename(), $this->configComment());

        return $this;
    }

    /**
     * The filename for Storage to use
     * @return string
     */
    protected function filename()
    {
        return 'dnsmasq.d/'.$this->provisioning_record->port_tag.'.conf';
    }

    /**
     * Add the config lines to the file
     * @return this
     */
    protected function fillFile()
    {
        foreach ($this->options() as $option)
        {
            $this->storage->append($this->filename(), $option);
        }

        return $this;
    }

    /**
     * The configured lease time beats the system/environment lease time
     * @return string The lease time for this record
     */
    public function getDhcpDefaultLeaseTime()
    {
        return (GaSetting::where('name', 'dhcp_default_lease_time')->first()) ?
            (GaSetting::where('name', 'dhcp_default_lease_time')->first())->value :
            config('goldaccess.settings.dhcp_default_lease_time');
    }

    /**
     * @return  string The dns servers
     */
    public function getDns()
    {
        return $this->provisioning_record->ip_address->subnet->dns_servers;
    }

    /**
     * @return  string The gateway
     */
    public function getGateway()
    {
        return $this->provisioning_record->ip_address->subnet->routers;
    }

    /**
     * @return  string The IP address to assign
     */
    public function getManagementIp()
    {
        return $this->provisioning_record->ip_address->address;
    }

    /**
     * @return  string The netmask
     */
    public function getNetmask()
    {
        return $this->provisioning_record->ip_address->subnet->subnet_mask;
    }

    /**
     * @return  string The Subscriber ID to match and use as the tag
     */
    protected function getSubscriberId()
    {
        return $this->provisioning_record->port->slot->aggregator->slug . '/' .
            $this->provisioning_record->port->slot->slot_number . '/' .
            '1/' .
            $this->provisioning_record->port->port_number;
    }

    /**
     * The options we assign all new management IPs
     * @return array
     */
    public function options()
    {
        $subscriberId = $this->getSubscriberId();
        $ip = $this->getManagementIp();
        $netmask = $this->getNetmask();
        $leasetime = $this->getDhcpDefaultLeaseTime();
        $gateway = $this->getGateway();
        $dns = $this->getDns();

        return [
            'dhcp-subscrid=set:"' . $subscriberId . '","' . $subscriberId . '"', // match subscriber id
            'dhcp-range=tag:"' . $subscriberId . '",tag:!internet-pool,' . $ip . ',' . $ip . ',' . $netmask . ',' . $leasetime, // the IP
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,3,' . $gateway, // The gateway
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,1,' . $netmask, // The netmask
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,5,' . $dns, // The dns server
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,67,' . $this->provisioning_record->dhcp_string,
            // 'option-logserver' => 'dhcp-option=tag:"BasementStack/1/3/2",7,10.0.0.4',
        ];
    }
}
