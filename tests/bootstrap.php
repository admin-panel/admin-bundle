<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;

error_reporting(E_ALL | E_STRICT);

/**
 * @var \Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$loader->add('FSi\\Component\\Reflection\\Tests', __DIR__);
$loader->add('FSi\\Component\\Metadata\\Tests', __DIR__);
$loader->add('AdminPanel\\Symfony\\AdminBundle\\Tests', __DIR__);
