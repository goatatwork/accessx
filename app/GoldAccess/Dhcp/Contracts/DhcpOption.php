<?php

namespace App\GoldAccess\Dhcp\Contracts;

interface DhcpOption
{
    /**
     * Create the dhcp option content
     * @param  string $dnsmasq_tag
     * @param  string $value
     * @return string
     */
    public static function make($dnsmasq_tag, $value);
}
