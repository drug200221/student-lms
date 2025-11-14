<?php

use Ox3a\Common\StudentApplication;

/** @var StudentApplication $app */
$app = require_once __DIR__ . '/../app.php';

$app->bootstrap();
$app->run();
