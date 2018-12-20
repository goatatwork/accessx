<?php

namespace App\GoldAccess\Dhcp\Options;

use App\GoldAccess\Dhcp\Contracts\DhcpOption;

class Option43 implements DhcpOption
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
     * @param  string $dnsmasq_tag
     * @param  string $value
     * @return string
     */
    public static function make($dnsmasq_tag, $value)
    {
        $prefix = (self::$forced) ? 'dhcp-option-force=' : 'dhcp-option=';

        $tag = 'tag:"' . $dnsmasq_tag . '",';

        $option = self::$option_number . ',';

        return $prefix . $tag . $option . $value;
    }
}
