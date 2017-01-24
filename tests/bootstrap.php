<?php

error_reporting(E_ALL | E_STRICT);

/**
 * @var \Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('FSi\\Component\\Reflection\\Tests', __DIR__);
$loader->add('FSi\\Component\\Metadata\\Tests', __DIR__);
$loader->add('FSi\\Bundle\\DataGridBundle\\Tests', __DIR__);
