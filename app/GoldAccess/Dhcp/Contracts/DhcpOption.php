<?php

namespace App\GoldAccess\Dhcp\Contracts;

use App\Subnet;
use App\GoldAccess\Dhcp\Contracts\Deployable;

abstract class DhcpOption
{
    /**
     * Create the dhcp option content
     * @param  $deployable A class that implements Deployable
     * @return string
     */
    abstract public static function make(Deployable $deployable);

    /**
     * Get the filename to write to for this option
     * @param  $deployable A class that implements Deployable
     * @return string
     */
    abstract public static function getFilename(Deployable $deployable);

    /**
     * @param  Subnet $subnet [description]
     * @return string
     */
    protected static function subnetSlug(Subnet $subnet)
    {
        return $subnet->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $subnet->network_address . '_' . $subnet->cidr);
    }
}
