<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MenuExtensionPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->processMainMenuExtensionConfig($container);
        $this->processToolsMenuExtensionConfig($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processMainMenuExtensionConfig(ContainerBuilder $container)
    {
        $serviceId = $container->getParameter('admin.main_menu_extension_service');

        if ($serviceId) {
            $menuExtension = $container->findDefinition($serviceId);

            $definition = $container->findDefinition('admin.menu.builder.main');
            $definition->addMethodCall('setMenuExtension', [$menuExtension]);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processToolsMenuExtensionConfig(ContainerBuilder $container)
    {
        $serviceId = $container->getParameter('admin.tools_menu_extension_service');

        if ($serviceId) {
            $menuExtension = $container->findDefinition($serviceId);

            $definition = $container->findDefinition('admin.menu.builder.tools');
            $definition->addMethodCall('setMenuExtension', [$menuExtension]);
        }
    }
}