<?php

namespace App\GoldAccess\Dhcp\Options;

use App\Subnet;
use App\GoldAccess\Dhcp\Contracts\Deployable;
use App\GoldAccess\Dhcp\Contracts\DhcpOption;

class Option43 extends DhcpOption
{
    /**
     * Should DHCP force this option to be sent
     * has not requested it
     * @var boolean
     */
    protected static $forced = true;

    /**
     * DHCP Option number
     * @var integer
     */
    protected static $option_number = 43;

    /**
     * Create the line that will be used in the dnsmasq config file
     * @param  \App\Subnet $subnet
     * @return string
     */
    public static function make(Deployable $subnet)
    {
        $prefix = (self::$forced) ? 'dhcp-option-force=' : 'dhcp-option=';

        $tag = 'tag:"' . self::subnetSlug($subnet) . '",';

        $option = self::$option_number . ',';

        return $prefix . $tag . $option . config('goldaccess.settings.acs_url');
    }

    /**
     * Get the filename to write to for this option
     * @param  \App\Subnet $subnet
     * @return string
     */
    public static function getFilename(Deployable $subnet)
    {
        return self::subnetSlug($subnet) . '-option43.conf';
    }

}
