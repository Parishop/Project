<?php
return [
    'translator'        => [
        'basePath' => '/',
    ],
    'resolver'          => [
        'type'      => 'group',
        'resolvers' => [
            'app' => [
                'type'     => 'prefix',
                'defaults' => [
                    'bundle' => 'app',
                ],
                'resolver' => [
                    'type' => 'mount',
                    'name' => 'app',
                ],
            ],
        ],
    ],
    'exceptionResponse' => [
        'template' => 'framework:http/exception',
    ],
    'notFoundResponse'  => [
        'template' => 'framework:http/notFound',
    ],
];

