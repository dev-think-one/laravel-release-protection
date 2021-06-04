<?php

return [
    'email' => [
        'envs' => [
            'default' => [
                // 'local',
                // 'development',
                'production',
            ],
            // 'web' => 'production'
        ],

        'emails' => [
            'default' => explode(',', env('RPROTECT_TESTERS_EMAILS', '')),
            // 'web' => explode(',', env('RPROTECT_WEB_TESTERS_EMAILS', '')),
        ],

        'msg' => [
            'not_logged' => [
                'default' => 'Restricted area',
            ],
            'restricted' => [
                'default' => 'You are not allowed to visit this directory.',
            ],
        ],
        'status' => [
            'not_logged' => [
                'default' => 403,
            ],
            'restricted' => [
                'default' => 403,
            ],
        ],
    ],

    'ip' => [
        'envs' => [
            'default' => [
                // 'local',
                // 'development',
                'production',
            ],
        ],

        'ips' => [
            'default' => explode(',', env('RPROTECT_FIRST_PARTY_IPS', '127.0.0.1')),
        ],

        'msg' => [
            'restricted' => [
                'default' => 'You are not allowed to visit this directory.',
            ],
        ],
        'status' => [
            'restricted' => [
                'default' => 403,
            ],
        ],
    ],
];
