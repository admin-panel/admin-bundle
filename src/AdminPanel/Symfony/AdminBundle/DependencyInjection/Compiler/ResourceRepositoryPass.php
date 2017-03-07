<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ResourceRepositoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasExtension('admin_panel_resource_repository')) {
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
            $loader->load('context/resource.xml');
        }
    }
}
