<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use AdminPanel\Symfony\AdminBundle\Exception\ResourceRepository\CompilerPassException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ResourcePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resources = array();
        foreach ($container->findTaggedServiceIds('resource.type') as $serviceId => $tag) {
            if (!isset($tag[0]['alias'])) {
                throw new CompilerPassException(sprintf('Service %s missing alias attribute', $serviceId));
            }

            $resourceService = $container->getDefinition($serviceId);
            $resources[$tag[0]['alias']] = $resourceService->getClass();
        }

        $container->setParameter('admin_resource_repository.resource.types', $resources);
    }
}