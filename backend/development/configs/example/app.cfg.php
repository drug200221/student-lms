<?php

return [
    'debug'     => false,
    'version'   => '0.00.0',
    'path'      => __DIR__ . '/../..',
    'defaultPages' => [
        'admin' => [
            'route' => 'adminUsers'
        ],
    ],
    'resources' => [
        'layout' => [
            'template' => 'default',
            'dir'      => __DIR__ . '/../../vendor/0x3a/student-common/src/Resources/layouts',
        ],
    ],
    'crypto'    => [
        'key' => 'secret',
    ],
];
