<?php
return [
    'type'      => 'group',
    'resolvers' => [
        'id'        => [
            'type' => 'pattern',
            'path' => '<processor>/<action>/<id>(/)',
        ],
        'action'    => [
            'type' => 'pattern',
            'path' => '<processor>/<action>(/)',
        ],
        'processor' => [
            'type'     => 'pattern',
            'path'     => '<processor>(/)',
            'defaults' => [
                'action' => 'default',
            ],
        ],
        'default'   => [
            'type'     => 'pattern',
            'path'     => '',
            'defaults' => [
                'processor' => 'home',
                'action'    => 'default',
            ],
        ],
    ],
];

