<?php
return [
    'database' => [
        'default' => [
            'driver'   => '',
            'adapter'  => '',
            'database' => '',
            'host'     => '',
            'port'     => '',
            'user'     => '',
            'password' => '',
        ],
    ],
    'mailer'   => [
        'type'             => 'native',
        'hostname'         => 'localhost',
        'port'             => '25',
        'username'         => null,
        'password'         => null,
        'encryption'       => null, // 'ssl' and 'tls' are supported
        'timeout'          => null, // timeout in seconds, defaults to 5
        'sendmail_command' => null,
        'mail_parameters'  => null,
    ],
    'social'   => [
        'facebook' => [
            'appId'     => '',
            'appSecret' => '',
            'scope'     => '',
        ],
        'google'   => [
            'appId'     => '',
            'appSecret' => '',
            'scope'     => '',
        ],
        'vk'       => [
            'appId'     => '',
            'appSecret' => '',
            'scope'     => '',
        ],
        'twitter'  => [
            'consumerKey'    => '',
            'consumerSecret' => '',
        ],
    ],
];

