<?php

use Ox3a\Common\StudentApplication;
use Ox3a\CommonModule\CommonModule;

require __DIR__ . '/../vendor/autoload.php';

$app = StudentApplication::init(
    [
        'configDirs' => [
            __DIR__ . '/configs',
            __DIR__ . '/../vendor/0x3a/student-app/src/Common/configs',
        ],
        'modules'    => [
            new CommonModule(),
            new \Psk\LmsModule\LmsModule(),
        ],
    ]
);

return $app;
