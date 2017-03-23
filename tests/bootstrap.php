<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\HttpKernel\Kernel;

if (version_compare(Kernel::VERSION, '2.8.0', '<')) {
    error_reporting(E_ALL ^ E_USER_DEPRECATED);
} else {
    error_reporting(E_ALL | E_STRICT);
}

const VAR_DIR = __DIR__ . '/var';

/**
 * @var \Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$loader->add('AdminPanel\\Symfony\\AdminBundle\\Tests', __DIR__);
$loader->add('AdminPanel\\Component\\', __DIR__);
