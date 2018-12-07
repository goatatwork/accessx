<?php
/**
 * This file is part of the GoldAccess package.
 *
 * @author     Goat
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Via AppServiceProvider, settings are read in from the ga_settings table
    | and inserted into the config as 'goldaccess.settings.settingname'. The
    | data settings have a higher priority than the values in this config file.
    | As you can see, the values here can usually be overridden with
    | environment variables.
     */
    'settings' => [
        'ga_devmode' => env('GA_DEVMODE', false),
        'dhcp_default_lease_time' => env('DHCP_DEFAULT_LEASE_TIME', '1800'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Dockerbot Configuration
    |--------------------------------------------------------------------------
    |
    | Configured Dockerbot's operational parameters
    |
    */
    'dockerbot' => [
        'services' => [
            'dhcp' => [
                'container_name' => env('DOCKERBOT_DHCP_CONTAINERNAME', 'accessx_dhcp'),
            ],
            'nginx' => [
                'container_name' => env('DOCKERBOT_NGINX_CONTAINERNAME', 'accessx_nginx'),
            ],
        ],
    ],

    'onts' => [
        'factory' => [
            'user' => env('FACTORY_DEFAULT_ONT_USER', 'admin'),
            'password' => env('FACTORY_DEFAULT_ONT_PASSWORD', 'password')
        ],
        'defaults' => [
            'user' => env('DEFAULT_ONT_USER', 'admin'),
            'password' => env('DEFAULT_ONT_PASSWORD', 'password')
        ]
    ]
];
