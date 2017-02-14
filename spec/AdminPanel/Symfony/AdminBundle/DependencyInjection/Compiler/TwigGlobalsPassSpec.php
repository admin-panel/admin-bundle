<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TwigGlobalsPassSpec extends ObjectBehavior
{
    public function let(ContainerBuilder $container, Definition $def)
    {
        $container->hasDefinition('twig')->willReturn(true);
        $container->findDefinition('twig')->willReturn($def);
    }

    public function it_adds_globals(ContainerBuilder $container, Definition $def)
    {
        $container->getParameter(Argument::any())->willReturn('test');
        $def->addMethodCall('addGlobal', Argument::containing('test'))->shouldBeCalled();

        $this->process($container);
    }
}
