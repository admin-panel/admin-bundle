<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Symfony\Core;

use AdminPanel\Component\DataSource\DataSourceAbstractExtension;
use AdminPanel\Component\DataSource\Extension\Symfony\Core\EventSubscriber\BindParameters;

/**
 * Main extension for all Symfony based extensions. Its main purpose is to
 * replace binded Request object into array.
 */
class CoreExtension extends DataSourceAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new BindParameters(),
        ];
    }
}
