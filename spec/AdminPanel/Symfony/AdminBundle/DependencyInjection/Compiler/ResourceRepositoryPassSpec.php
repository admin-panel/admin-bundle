<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResourceRepositoryPassSpec extends ObjectBehavior
{
    public function it_does_nothing_when_there_is_no_resource_extension(ContainerBuilder $container)
    {
        $container->hasExtension('admin_panel_resource_repository')->willReturn(false);
        $this->process($container);
    }
}
