<?php
/**
 * This file is part of the GoldAccess package.
 *
 * @author     Goat
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Audit Implementation
    |--------------------------------------------------------------------------
    |
    | Define which Audit model implementation should be used.
    |
    */

    'implementation' => OwenIt\Auditing\Models\Audit::class,

    /*
    |--------------------------------------------------------------------------
    | Dockerbot Configuration
    |--------------------------------------------------------------------------
    |
    | Configured Dockerbot's operational parameters
    |
    */

    'dockerbot' => [

        /**
         * The host providing the docker API
         */
        'host' => env('DOCKER_HOST', 'tcp://127.0.0.1:4243'),

        'services' => [
            'dhcp' => [
                'container_name' => env('DOCKERBOT_DHCP_CONTAINERNAME', 'dnsmasq_server'),
            ],
        ],
    ],
];
