<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dnsmasq Server Information
    |--------------------------------------------------------------------------
    |
    | These are settings for the DHCP server on the network. The 'keyfile'
    | is used to point to the private ssh key on the local host that
    | corresponds with the remote user and who's public key has been
    | added to the remote 'user' authorized_keys file.
    |
    */

    'dhcp' => [
        'ip' => env('DHCP_IP', '127.0.0.1'),
        'user' => env('DHCP_USER', 'root'),
        'keyfile' => env('DHCP_KEYFILE', ''),
        'configfile' => env('DHCP_CONFIGFILE', '/etc/dnsmasq.conf'),
        'config_files_path' => env('DHCP_CONFIG_FILES_PATH', '/etc/dnsmasq.d'),
        'pid' => env('DHCP_PID', '/run/dnsmasq/dnsmasq.pid')
    ]
];
