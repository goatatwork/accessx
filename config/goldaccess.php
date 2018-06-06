<?php
/**
 * This file is part of the GoldAccess package.
 *
 * @author     Goat
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Dockerbot Configuration
    |--------------------------------------------------------------------------
    |
    | Configured Dockerbot's operational parameters
    |
    */
    'settings' => [
        'ga_devmode' => env('GA_DEVMODE', false)
    ],

    'dockerbot' => [
        'services' => [
            'dhcp' => [
                'container_name' => env('DOCKERBOT_DHCP_CONTAINERNAME', 'dnsmasq_server'),
            ],
            'nginx' => [
                'container_name' => env('DOCKERBOT_NGINX_CONTAINERNAME', 'goldaccess_nginx_1'),
            ],
        ],
    ],

    'onts' => [
        'defaults' => [
            'user' => env('DEFAULT_ONT_USER', 'admin'),
            'password' => env('DEFAULT_ONT_PASSWORD', 'password')
        ]
    ]
];
