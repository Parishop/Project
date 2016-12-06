<?php
return [
    'domains' => [
        'default' => [
            'repository' => 'app.user',
            'providers'  => [
                'session'  => [
                    'type' => 'http.session',
                ],
                'cookie'   => [
                    'type'             => 'http.cookie',
                    'persistProviders' => ['session'],
                    'tokens'           => [
                        'storage' => [
                            'type'            => 'database',
                            'table'           => 'tokens',
                            'defaultLifetime' => 3600 * 24 * 14,
                        ],
                    ],
                ],
                'password' => [
                    'type'             => 'login.password',
                    'persistProviders' => ['session'],
                ],
                'social'   => [
                    'type'             => 'social.oauth',
                    'persistProviders' => ['session'],
                ],
            ],
        ],
    ],
];

