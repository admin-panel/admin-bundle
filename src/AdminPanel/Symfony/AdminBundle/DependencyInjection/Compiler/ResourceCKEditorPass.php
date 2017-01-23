<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ResourceCKEditorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = new Definition('AdminPanel\Symfony\AdminBundle\Repository\Resource\Type\CKEditorType');
        $definition->addTag('resource.type', array('alias' => 'ckeditor'));

        $container->setDefinition('admin_resource_repository.resource.type.ckeditor', $definition);
    }
}
