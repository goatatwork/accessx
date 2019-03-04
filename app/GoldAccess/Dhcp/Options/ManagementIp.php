<?php

namespace App\GoldAccess\Dhcp\Options;

use App\GaSetting;
use App\ProvisioningRecord;
use App\GoldAccess\Dhcp\Contracts\Deployable;
use App\GoldAccess\Dhcp\Contracts\DhcpOption;

class ManagementIp extends DhcpOption
{
    /**
     * Create the content that will be used in the dnsmasq config file
     * @param  App\ProvisioningRecord
     * @return string
     */
    public static function make(Deployable $provisioning_record)
    {
        return (new self)->options($provisioning_record);
    }

    /**
     * Get the filename to write to for this option
     * @param  \App\Deployable $provisioning_record
     * @return string
     */
    public static function getFilename(Deployable $provisioning_record)
    {
        return $provisioning_record->port_tag . '.conf';
    }

    /**
     * The options we assign all new management IPs
     * @param  \App\Deployable $provisioning_record
     * @return array
     */
    protected function options(Deployable $provisioning_record)
    {
        $name = $provisioning_record->service_location->customer->customer_name;
        $id = $provisioning_record->service_location->customer->id;
        $subscriberId = $this->getSubscriberId($provisioning_record);
        $ip = $provisioning_record->ip_address->address;
        $netmask = $provisioning_record->ip_address->subnet->subnet_mask;
        $leasetime = $this->getDhcpDefaultLeaseTime();
        $gateway = $provisioning_record->ip_address->subnet->routers;
        $dns = $provisioning_record->ip_address->subnet->dns_servers;

        return '## Management IP for '.$name.' (ID:'.$id.')' . "\n" .
            'dhcp-subscrid=set:"' . $subscriberId . '","' . $subscriberId . '"' . "\n" .
            'dhcp-range=tag:"' . $subscriberId . '",tag:!internet-pool,' . $ip . ',' . $ip . ',' . $netmask . ',' . $leasetime . "\n" .
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,3,' . $gateway . "\n" .
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,1,' . $netmask . "\n" .
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,5,' . $dns . "\n" .
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,6,' . $dns . "\n" .
            'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,67,' . $provisioning_record->dhcp_string . "\n";
    }

    /**
     * The configured lease time beats the system/environment lease time
     * @return string The lease time for this record
     */
    protected function getDhcpDefaultLeaseTime()
    {
        return (GaSetting::where('name', 'dhcp_default_lease_time')->first()) ?
            (GaSetting::where('name', 'dhcp_default_lease_time')->first())->value :
            config('goldaccess.settings.dhcp_default_lease_time');
    }

    /**
     * @return  string The Subscriber ID to match and use as the tag
     * @param  \App\Deployable $provisioning_record
     */
    protected function getSubscriberId(Deployable $provisioning_record)
    {
        return $provisioning_record->port->slot->aggregator->slug . '/' .
            $provisioning_record->port->slot->slot_number . '/' .
            '1/' .
            $provisioning_record->port->port_number;
    }
}
